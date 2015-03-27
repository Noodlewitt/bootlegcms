<?php namespace Bootleg\Search;

class SearchController extends \BaseController{
    protected $client;
    public function __construct(){
        $this->client = \AWS::get('CloudSearchDomain', array(
            'endpoint' => 'search-localhost-urq7zxbwecst4c6jg3r4zaqvxi.us-east-1.cloudsearch.amazonaws.com'
        ));

    }

    public function anySearch($query){

        $result = $this->client->search(array('query' => $query.'~'));
        
        foreach($result['hits']['hit'] as $r){
            var_dump($r['fields']['title']);
        }
    }

    public function getAdd($fields=array(), $type="application/json"){
        //accepts an array of json objects to add to a list.
        //
        


        $docs['documents'] = '[{
            "fields" : {
                "directors" : [
                    "potato"
                ],
                "release_date" : "2013-01-18T00:00:00Z",
                "rating" : 9.9,
                "genres" : [
                    "Drama"
                ],
                "image_url" : "http://ia.media-imdb.com/images/M/MV5BMTQxNTc3NDM2MF5BMl5BanBnXkFtZTcwNzQ5NTQ3OQ@@._V1_SX400_.jpg",
                "plot" : "The story of a potato and it\'s adventures",
                "title" : "potato",
                "rank" : 1,
                "running_time_secs" : 5400,
                "actors" : [
                    "mr potato head",
                    "Barry White"
                ],
                "year" : 2016
            },
            "id" : "824655",
            "type" : "add"
        }]';
        $docs['contentType'] = 'application/json';

        $this->client->uploadDocuments($docs);
    }

    public function getRemove($id){
        //accepts an array of json objects to add to a list.
        $this->client->uploadDocuments($jsons, $type);
    }
}