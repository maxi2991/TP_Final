<?php

namespace DAO;

use Models\Movie as Movie;
use Models\Genre as Genre;
use DAO\IMovieDAO as IMovieDAO;
use DAO\Connection as Connection;


class MovieDAO implements IMovieDAO{

    private $connection;
    private $tableName="movies";


    public function add($movie){

        
        $exists=$this->search($movie->getTmdbID());

        
       
        if(!$exists){
            $sql = "INSERT INTO ".$this->tableName." (idMovie,title,originalTitle,voteAverage,overview,releaseDate,popularity,videoPath,adult,posterPath,backDropPath,originalLanguage,runtime,homepage,director,review, state) 
                                            VALUES (:idMovie,:title,:originalTitle,:voteAverage,:overview,:releaseDate,:popularity,:videoPath,:adult,:posterPath,:backDropPath,:originalLanguage,:runtime,:homepage,:director,:review, :state)";


            $parameters["idMovie"] = $movie->getTmdbId();
            $parameters["title"] = $movie->getTitle();
            $parameters["originalTitle"] = $movie->getOriginalTitle();
            $parameters["voteAverage"] = $movie->getVoteAverage();
            $parameters["overview"] = $movie->getDescription();
            $parameters["releaseDate"] = $movie->getReleaseDate();
            $parameters["popularity"] = $movie->getPopularity();
            $parameters["videoPath"] = $movie->getVideoPath();
            $parameters["adult"] = $movie->getAdult();
            $parameters["posterPath"] = $movie->getPoster();
            $parameters["backDropPath"] = $movie->getBackdropPath();
            $parameters["originalLanguage"] = $movie->getOriginalLanguage();
            $parameters["runtime"] = $movie->getRuntime();
            $parameters["homepage"] = $movie->getHomepage();
            $parameters["review"] = $movie->getReview();
            $directors = implode(" - ", $movie->getDirector());
            $parameters["director"] = $directors;
            $parameters["state"] = $movie->getState();

            try{

            $this->connection=Connection::getInstance();

            $result=$this->connection->executeNonQuery($sql,$parameters);

            if($result > 0){    /* agregar generos x peliculas a tabla intermedia */
                $this->addGenresXMovies($movie->getGenres(),$movie->getTmdbId());
            }
            
            return $result;

            }catch(\PDOException $ex){
                throw $ex;
            }
        }
    }



   public function addGenresXMovies($genres, $IdMovie){
       
        $sql = "INSERT INTO moviesxgenres (idMovie,idGenre) VALUES (:idMovie,:idGenre)";
        $result=null;

        foreach($genres as $genre){

            $parameters["idMovie"] = $IdMovie;
            $parameters["idGenre"] = $genre->getId();   
            
            try{
                $this->connection=Connection::getInstance();
                $result+=$this->connection->executeNonQuery($sql,$parameters);
            }catch(\PDOException $ex){
                throw $ex;
            }
        }
        
        return $result;
    }


    /* agregar o quitar del catalogo */
    public function setState($idMovie, $state){
        
        $exists=$this->search($idMovie);
      
        if($exists){
            $sql = "UPDATE ".$this->tableName." set state=:state WHERE idMovie=:idMovie";

            $parameters["state"] = $state;
            $parameters["idMovie"] = $idMovie;

            try{

            $this->connection=Connection::getInstance();

            $result=$this->connection->executeNonQuery($sql,$parameters);
            
            return $result;

            }catch(\PDOException $ex){
                throw $ex;
            }
        }
    }


    /* obtener todas las peliculas del DAO, activas o no */
    public function getAll(){
        try
        {

            # throw new \PDOException("testing catch on upper level");

            $movieList = array();

            $query = "SELECT * FROM ".$this->tableName;

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);
                        
            if($resultSet){
                $mapping= $this->map($resultSet);
                if(!is_array($mapping)){
                    array_push($movieList,$mapping);
                }else{
                $movieList=$mapping;
                }
            }
            
        }catch(\PDOException $ex)
        {
            throw $ex;
        }

        if(!empty($resultSet)){
     
            return $movieList; 
        }else{
            return null;
        }
    }




    /* obtener todas las peliculas del DAO, disponibles para shows */
    public function getAllStateOne(){
        try
        {
            # throw new \PDOException("testing catch on upper level");

            $movieList = array();

            $query = "SELECT * FROM movies WHERE state = 1 ORDER BY title";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);
                          
            if($resultSet){
                $mapping= $this->map($resultSet);
                if(!is_array($mapping)){
                    array_push($movieList,$mapping);
                }else{
                $movieList=$mapping;
                }
            }
        }
        catch(\PDOException $ex)
        {
            throw $ex;
        }

        if(!empty($resultSet)){
     
            return $movieList; 
        }else{
            return null;
        }
    }



    /* obtener todas las peliculas del DAO, dadas de baja logica */
    public function getAllStateZero(){
        try
        {
            $movieList = array();

            $query = "SELECT * FROM movies WHERE state = 0 ORDER BY title";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);
                          
            if($resultSet){
                $mapping= $this->map($resultSet);
                if(!is_array($mapping)){
                    array_push($movieList,$mapping);
                }else{
                $movieList=$mapping;
                }
            }
        }
        catch(\PDOException $ex)
        {
            throw $ex;
        }

        if(!empty($resultSet)){
     
            return $movieList; 
        }else{
            return null;
        }
    }



    /* Retorna las mejores 20 peliculas según valoración *//*
    public function getBestRated(){ 
        try
        {
            $movieList = array();

            $query = "SELECT * FROM movies WHERE posterPath IS NOT NULL ORDER BY movies.voteAverage DESC LIMIT 20";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

    
        }    
        catch(\PDOException $ex){
            throw $ex;
        }

        if($resultSet){
            $mapping= $this->map($resultSet);
            if(!is_array($mapping)){
                array_push($movieList,$mapping);
            }else{
            $movieList=$mapping;
            }
        }

        if(!empty($resultSet)){
            return $movieList; 
        }else{
            return null;
        }
    }*/


      /* Retorna las mejores 5 peliculas según popularidad */ /*
      public function getMostPopular(){
        try
        {
            $movieList = array();

            $query = "SELECT * FROM movies WHERE backDropPath IS NOT NULL ORDER BY movies.popularity DESC LIMIT 5";


            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);
            
            
         
        }
        catch(\PDOException $ex)
        {
            throw $ex;
        }

        if($resultSet){
            $mapping= $this->map($resultSet);
            if(!is_array($mapping)){
                array_push($movieList,$mapping);
            }else{
            $movieList=$mapping;
            }
        }

        if(!empty($resultSet)){
            return $movieList; 
        }else{
            return null;
        }
    }*/



    /* buscar si existe o no una pelicula en el DAO */
    public function search($tmdbId){
        try
        {
            $query = "SELECT * FROM movies WHERE idMovie= :idMovie";
            $parameters["idMovie"] = $tmdbId;

            $this->connection = Connection::getInstance();

            $result = $this->connection->execute($query, $parameters);
        }
        catch(\PDOException $ex)
        {
            throw $ex;
        }

        if(!empty($result)){
            return $this->map($result);
        }else{
            return null;
        }
    }



    /* retorna por coincidencia de palabra en el titulo */
    public function searchByWord($word){

        try
        {
            $query = "SELECT * FROM movies 
                        WHERE movies.title LIKE '%$word%'";


            $this->connection = Connection::getInstance();

            $result = $this->connection->execute($query);
            
            if($result){
                
                $mapping = $this->map($result);  

                return $mapping;
            }
            else{
                return null;
            }
        }
        catch(\PDOException $ex)
        {
            throw $ex;
        }
    }
    


      /* retorna las peliculas por genero*/
    public function getByGenre($idGenre){
        try
        {
            $movieList = array();

            $query = "SELECT * FROM movies AS m INNER JOIN moviesxgenres AS mxg ON m.idMovie = mxg.idMovie WHERE mxg.idGenre=:idGenre";
            $parameters["idGenre"] = $idGenre;

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);

            if($resultSet){
                $mapping= $this->map($resultSet);
                if(!is_array($mapping)){
                    array_push($movieList,$mapping);
                }else{
                $movieList=$mapping;
                }
            }
        }
        catch(\PDOException $ex)
        {
            throw $ex;
        }
        
        if(!empty($resultSet)){
            return $movieList;
        }else{
            return null;
        }
    }


    


    protected function getMovieGenres($movie){
        try{
            $genreList= array();

            $query = "SELECT g.idGenre, g.name FROM moviesxgenres AS mxg JOIN genres AS g ON mxg.idGenre=g.idGenre WHERE mxg.idMovie=:idMovie";

            $parameters["idMovie"] = $movie->getTmdbId();

            $this->connection = Connection::getInstance();

            $result= $this->connection->execute($query, $parameters);
            
       
            if($result){
                $mapping= $this->mapGenre($result);
                if(!is_array($mapping)){
                    array_push($genreList,$mapping);
                }else{
                $genreList=$mapping;
                }
            }

        }
        catch(\PDOException $ex)
        {
            throw $ex;
        }

        if(!empty($result)){
            return $genreList;
        }else{
            return null;
        }
        
    }

    protected function map($value){
        $value=is_array($value) ? $value: array();
        
        $result= array_map(function ($p){
            $movie=new Movie();
            $movie->setTmdbId($p["idMovie"]);
            $movie->setTitle($p["title"]);
            $movie->setOriginalTitle($p["originalTitle"]);
            $movie->setVoteAverage($p["voteAverage"]);
            $movie->setDescription($p["overview"]);
            $movie->setReleaseDate($p["releaseDate"]);
            $movie->setPopularity($p["popularity"]);
            $movie->setVideoPath($p["videoPath"]);
            $movie->setAdult($p["adult"]);
            $movie->setPoster($p["posterPath"]);
            $movie->setBackdropPath($p["backDropPath"]);
            $movie->setOriginalLanguage($p["originalLanguage"]);
            $movie->setRuntime($p["runtime"]);
            $movie->setHomepage($p["homepage"]);
            $movie->setDirector($p["director"]);
            $movie->setReview($p["review"]);
            $movie->setState($p["state"]);

            $genres=$this->getMovieGenres($movie);
            $movie->setGenres($genres);

            return $movie;
        },$value);

        if(!empty($result)){
            return count($result)>1 ? $result: $result["0"];        
        }else{
            return null;
        }
        
    }


    protected function mapGenre($value){
        $value=is_array($value) ? $value: array();
        
        $result=array_map(function($p){
            $genre=new Genre();
            $genre->setId($p['idGenre']);
            $genre->setName($p["name"]);
     
            return $genre;
        },$value);

        return count($result)>1 ? $result: $result["0"];
    }

}?>