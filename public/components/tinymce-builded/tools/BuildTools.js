/**
 * Various build tools for Jake.
 */

/*jshint smarttabs:true, undef:true, node:true, latedef:true, curly:true, bitwise:true */
"use strict";

var fs = require("fs");
var path = require("path");
var child_process = require("child_process");

function extend(a, b) {
	if (b) {
		for (var name in b) {
			a[name] = b[name];
		}
	}

	return a;
}

function getFileModTime(filePath) {
	return fs.existsSync(filePath) ? fs.statSync(filePath).mtime.getTime() : 0;
}

function setFileModTime(filePath, time) {
	return fs.utimesSync(filePath, new Date(time), new Date(time));
}

exports.uglify = function(options) {
	var UglifyJS = require("uglify-js");
	var filePaths = [];

	options = extend({
		mangle : true,
		toplevel : false,
		no_functions : false,
		ascii_only: true
	}, options);

	var toFileModTime = getFileModTime(options.to);
	var fromFileModTime = 0;

	// Combine JS files
	if (options.from instanceof Array) {
		options.from.forEach(function(filePath) {
			if (options.sourceBase) {
				filePath = path.join(options.sourceBase, filePath);
			}

			filePaths.push(filePath);

			fromFileModTime = Math.max(fromFileModTime, getFileModTime(filePath));
		});
	} else {
		filePaths.push(options.from);
		fromFileModTime = getFileModTime(options.from);
	}

	if (options.force === true || fromFileModTime !== toFileModTime) {
		var result = UglifyJS.minify(filePaths, {
		});

		fs.writeFileSync(options.to, result.code);
		setFileModTime(options.to, fromFileModTime);
	}
};

exports.less = function (options) {
	var source = "", lastMod = 0, less = require('less');

	var sourceFile = options.from;
	var outputFile = options.toCss;

	options = extend({
		compress: true,
		yuicompress: true,
		optimization: 1,
		silent: false,
		paths: [],
		color: true,
		strictImports: false
	}, options);

	var parser = new less.Parser({
		paths: [options.baseDir || path.dirname(sourceFile)],
		filename: path.basename(sourceFile),
		optimization: options.optimization,
		strictImports: options.strictImports
	});

	// Parse one or multiple files
	if (sourceFile instanceof Array) {
		sourceFile.forEach(function(sourceFile) {
			lastMod = Math.max(lastMod, getFileModTime(path.join(options.baseDir, sourceFile)));
		});

		if (options.force !== true && lastMod === getFileModTime(outputFile)) {
			return;
		}

		sourceFile.forEach(function(sourceFile) {
			source += fs.readFileSync(path.join(options.baseDir, sourceFile), 'utf-8').toString().replace(/^\uFEFF/g, '');
		});

		if (options.toLessDev) {
			var lessImportCode = "";

			sourceFile.forEach(function(sourceFile) {
				lessImportCode += '@import "' + sourceFile + '";\n';
			});

			fs.writeFileSync(options.toLessDev, lessImportCode);
		}

		if (options.toLess) {
			fs.writeFileSync(options.toLess, source);
		}
	} else {
		lastMod = getFileModTime(sourceFile);
		if (options.force !== true && lastMod === getFileModTime(outputFile)) {
			return;
		}

		source = fs.readFileSync(sourceFile).toString();
	}

	parser.parse(source, function (err, tree) {
		if (err) {
			less.writeError(err, options);
			return;
		}

		fs.writeFileSync(outputFile, tree.toCSS({
			compress: options.compress,
			yuicompress: options.yuicompress
		}));

		setFileModTime(outputFile, lastMod);
	});
};

exports.yuidoc = function (sourceDir, outputDir, options) {
	var Y = require('yuidocjs');

	if (!(sourceDir instanceof Array)) {
		sourceDir = [sourceDir];
	}

	options = extend({
		paths: sourceDir,
		outdir: outputDir,
		time: false
	}, options);

	var starttime = new Date().getTime();
	var json = (new Y.YUIDoc(options)).run();

	var builder = new Y.DocBuilder(options, json);
	builder.compile(function() {
		var endtime = new Date().getTime();

		if (options.time) {
			Y.log('Completed in ' + ((endtime - starttime) / 1000) + ' seconds' , 'info', 'yuidoc');
		}
	});
};

exports.jshint = function (options) {
	var jshint = require('jshint').JSHINT, exclude, count = 0;

	function removeComments(str) {
		str = str || "";
		str = str.replace(/\/\*(?:(?!\*\/)[\s\S])*\*\//g, "");
		str = str.replace(/\/\/[^\n\r]*/g, ""); // Everything after '//'

		return str;
	}

	options = options || {};

	var color = function(s, c){
		return (color[c].toLowerCase()||'') + s + color.reset;
	};

	color.reset = '\u001b[39m';
	color.red = '\u001b[31m';
	color.yellow = '\u001b[33m';
	color.green = '\u001b[32m';

	if (fs.existsSync(".jshintrc")) {
		options = extend(JSON.parse(removeComments("" + fs.readFileSync(options.jshintrc || ".jshintrc"))), options);
		delete options.jshintrc;
	}

	if (options.exclude) {
		exclude = options.exclude;
		delete options.exclude;
	}

	function process(filePath) {
		var stat = fs.statSync(filePath);

		// Don't hint on minified files
		if (/\.min\.js$/.test(filePath)) {
			return;
		}

		if (exclude && exclude.indexOf(filePath) != -1) {
			return;
		}

		if (/\.js$/.test(filePath)) {
			if (!jshint(fs.readFileSync(filePath).toString(), options, {define: true})) {
				// Print the errors
				console.log(color('Errors in file ' + filePath, 'red'));
				var out = jshint.data(),
				errors = out.errors;
				Object.keys(errors).forEach(function(error){
					error = errors[error];

					if (error) {
						console.log('line: ' + error.line + ':' + error.character+ ' -> ' + error.reason );
						console.log(color(error.evidence,'yellow'));
						count++;
					}
				});
			}
		}
	}

	var patterns = options.patterns;
	delete options.patterns;
	patterns.forEach(function(filePath) {
		require("glob").sync(filePath).forEach(process);
	});

	if (count > 0) {
		process.exit(1);
	}
};

exports.zip = function (options) {
	var ZipWriter = require('moxie-zip').ZipWriter;
	var archive = new ZipWriter();

	function process(filePath, zipFilePath) {
		var args, stat;

		if (filePath instanceof Buffer) {
			archive.addData(path.join(options.baseDir, zipFilePath), filePath);
			return;
		}

		stat = fs.statSync(filePath);
		zipFilePath = zipFilePath || filePath;
		filePath = filePath.replace(/\\/g, '/');
		zipFilePath = zipFilePath.replace(/\\/g, '/');

		if (options.pathFilter) {
			args = {filePath: filePath, zipFilePath: zipFilePath};
			options.pathFilter(args);
			zipFilePath = args.zipFilePath;
		}

		if (options.exclude) {
			for (var i = 0; i < options.exclude.length; i++) {
				var pattern = options.exclude[i];

				if (pattern instanceof RegExp) {
					if (pattern.test(filePath)) {
						return;
					}
				} else {
					if (filePath === pattern) {
						return;
					}
				}
			}
		}

		if (stat.isFile()) {
			var data = fs.readFileSync(filePath);

			if (options.dataFilter) {
				args = {filePath: filePath, zipFilePath: zipFilePath, data: data};
				options.dataFilter(args);
				data = args.data;
			}

			archive.addData(path.join(options.baseDir, zipFilePath), data);
		} else if (stat.isDirectory()) {
			fs.readdirSync(filePath).forEach(function(fileName) {
				if (fileName.charAt(0) != '.') {
					process(path.join(filePath, fileName), path.join(zipFilePath, fileName));
				}
			});
		}
	}

	options.baseDir = (options.baseDir || '').replace(/\\/g, '/');

	options.from.forEach(function(filePath) {
		if (filePath instanceof Array) {
			process(filePath[0], filePath[1]);
		} else {
			process(filePath);
		}
	});

	archive.saveAs(options.to);
};

exports.compileAmd = function (options) {
	//options.verbose = true;
	require("amdlc").compile(options);
};

exports.parseLessDocs = function (filePath) {
	var matches, docCommentRegExp = /\/\*\*([\s\S]+?)\*\//g, lessFiles = [];
	var source = fs.readFileSync(filePath).toString();

	for (matches = docCommentRegExp.exec(source); matches; matches = docCommentRegExp.exec(source)) {
		var docComment = matches[1];

		var lessMatch = /\@\-x\-less\s+(.+)/g.exec(docComment);
		if (lessMatch) {
			lessFiles.push(lessMatch[1]);
		}
	}

	return lessFiles;
};

exports.getReleaseDetails = function (filePath) {
	var firstLine = ("" + fs.readFileSync(filePath)).split('\n')[0];

	return {
		version: /^Version ([0-9xabrc.]+)/.exec(firstLine)[1],
		releaseDate: /^Version [^\(]+\(([^\)]+)\)/.exec(firstLine)[1]
	};
};

exports.instrumentFile = function(options) {
	var Instrument = require('coverjs').Instrument;
	var source = "" + fs.readFileSync(options.from);

	fs.writeFileSync(options.to, new Instrument(source, {
		name: options.from
	}).instrument());
};

exports.eslint = function(options) {
	var eslint = require('eslint').cli, args = [];

	function globFiles(patterns) {
		var files = [], glob = require("glob");

		if (patterns instanceof Array) {
			patterns.forEach(function(pattern) {
				if (pattern[0] == '!') {
					glob.sync(pattern.substr(1)).forEach(function(file) {
						var idx = files.indexOf(file);

						if (idx != -1) {
							files.splice(idx, 1);
						}
					});
				} else {
					files = files.concat(glob.sync(pattern));
				}
			});
		} else {
			globFiles([patterns]);
		}

		return files;
	}

	if (options.config) {
		args.push('--config', path.resolve(options.config));
	}

	if (options.rulesdir) {
		args.push('--rulesdir', options.rulesdir);
	}

	if (options.format) {
		args.push('--format', options.format);
	}

	eslint.execute(args.concat(globFiles(options.src)).join(' '));
};

exports.nuget = function(options) {
	var http = require("http"), fs = require("fs");
	var child_process = require("child_process");
	var args = [];

	if (!/^win/.test(process.platform)) {
		return;
	}

	function download(fromUrl, toPath, callback) {
		var req = http.get(fromUrl, function(response) {
			var location = response.headers.location;

			if (location) {
				req.abort();
				download(require("url").resolve(fromUrl, location), toPath, callback);
			} else {
				var file = fs.createWriteStream(toPath);
				file.on('finish', callback);
				response.pipe(file);
			}
		});
	}

	function execNuget(nuspec, args) {
		child_process.execFile(options.cmd, ["pack", nuspec].concat(args), function (error, stdout, stderr) {
			if (!options.quiet) {
				if (error !== null) {
					console.log("NuGet exec error: " + error);
				}

				if (stdout !== null) {
					console.log(stdout);
				}

				if (stderr !== null) {
					console.log(stderr);
				}
			}
		});
	}

	if (!fs.existsSync(options.cmd)) {
		download("http://nuget.org/nuget.exe", options.cmd, function() {
			if (fs.existsSync(options.cmd)) {
				exports.nuget(options);
			}
		});

		return;
	}

	if (options.version) {
		args.push("-Version", options.version);
	}

	if (options.dest) {
		args.push("-OutputDirectory", options.dest);
	}

	if (options.nuspec) {
		if (options.nuspec instanceof Array) {
			options.nuspec.forEach(function(nuspec) {
				execNuget(nuspec, args);
			});
		} else {
			execNuget(options.nuspec, args);
		}
	}
};

exports.phantomjs = function(args) {
	var childProcess = require('child_process');
	var phantomjs = require('phantomjs');
	var binPath = phantomjs.path;

	childProcess.execFile(binPath, args, function(err, stdout, stderr) {
		console.log(stdout);
		console.log(stderr);
	});
};

exports.jscs = function(args) {
	var Checker = require('jscs');
	var checker = new Checker();

	checker.registerDefaultRules();
	checker.configure(JSON.parse(fs.readFileSync(args.configFile)));
	checker.checkPath(args.src).then(function(results) {
		var errorsCollection = [].concat.apply([], results);
		var count = 0;

		errorsCollection.forEach(function(errors) {
			if (!errors.isEmpty()) {
				errors.getErrorList().forEach(function(error) {
					console.log(errors.explainError(error, true) + '\n');
					count++;
				});
			}
		});

		if (count > 0) {
			console.log("jscs errors found: " + count);
			process.exit(1);
		}

		if (args.oncomplete) {
			args.oncomplete();
		}
	});
};