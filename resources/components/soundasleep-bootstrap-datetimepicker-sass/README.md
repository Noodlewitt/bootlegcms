# Bootstrap v3 datetimepicker widget using SASS

![DateTimePicker](http://i.imgur.com/nfnvh5g.png)

### [⇢ View the manual and demos](http://eonasdan.github.io/bootstrap-datetimepicker/)

This is a fork of [eonasdan-bootstrap-datetimepicker](https://github.com/Eonasdan/bootstrap-datetimepicker) that simply provides SASS equivalents of `src/less/*.less` files.

## Quick installation

### [bower](http://bower.io)

Run the following command:
```
bower install soundasleep-bootstrap-datetimepicker-sass#latest --save
```

Include necessary scripts and styles:
```html
<head>
  <!-- ... -->
  <script type="text/javascript" src="/bower_components/jquery/jquery.min.js"></script>
  <script type="text/javascript" src="/bower_components/moment/min/moment.min.js"></script>
  <script type="text/javascript" src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/bower_components/soundasleep-bootstrap-datetimepicker-sass/build/js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/bower_components/soundasleep-bootstrap-datetimepicker-sass/build/css/bootstrap-datetimepicker.min.css" />
</head>
```

## Things that should probably be fixed

1. I can't get `make` to work locally on Windows, it crashes. Probably a good idea to use a different build system, maybe `grunt`?
2. The `make` file only generates from LESS, not SASS. It looks like there are no SASS compilation binaries for Windows... maybe need to use `grunt` for `grunt-sass`?
3. There are no tests, not even an example HTML file to demonstrate how it works (I guess that's what the Github.io pages were for). It would be good to add them.
4. An issue I'd like to fix: [Add options to disable displaying Months or Years views](https://github.com/Eonasdan/bootstrap-datetimepicker/issues/252)
