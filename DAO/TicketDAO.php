<?php
    namespace DAO;
    
    use DAO\ITicketDAO as ITicketDAO;
    use Models\Show as Show;
    use Models\Bill as Bill;
    use Models\Seat as Seat;
    use Models\Room as Room;
    use Models\Cinema as Cinema;
    use Models\Movie as Movie;
    use Models\Ticket as Ticket;
    use Models\User as User;
    use DAO\Connection as Connection;
  


    class TicketDAO implements ITicketDAO{
        private $connection;
        

        function __construct(){}


        public function add($ticket){
            $sql = "INSERT INTO tickets (idBill, idShow, seat, priceTicket, qrCode)
                            VALUES (:idBill, :idShow, :seat, :price, :qrCode)";
            
            $parameters['idBill']=$ticket->getBill()->getIdBill();
            $parameters['idShow']=$ticket->getShow()->getIdShow();
            $parameters['seat']=$ticket->getSeat()->getIdSeat();
            $parameters['price']=$ticket->getPrice(); 
            $parameters['qrCode']=$ticket->getQrCode(); 
    
            try{
                $this->connection = Connection::getInstance();
                return $this->connection->executeNonQuery($sql, $parameters);
            }
            catch(\PDOException $ex){
                throw $ex;
            }
        }  


        public function getAll(){

            try
            {
                $ticketList = array();
    
                $query = "SELECT * FROM tickets t
                INNER JOIN bills b ON t.idBill=b.idBill
                INNER JOIN users u ON u.idUser=b.idUser
                INNER JOIN seats se ON t.seat=se.idSeat
                INNER JOIN shows s  ON t.idShow=s.idShow
                INNER JOIN movies m ON s.idMovie = m.idMovie 
                INNER JOIN rooms r ON s.idRoom = r.idRoom 
                INNER JOIN cinemas c ON r.idCinema = c.idCinema";
    
                $this->connection = Connection::getInstance();
    
                $result = $this->connection->execute($query);
              
                if($result){
                    foreach($result as $value){
                        $mapping = $this->mapTicket($value);  
                        array_push($ticketList, $mapping);
                    }
                    return $ticketList;
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
        
        /*
        public function getbyShow($idShow){

            try
            {
                $ticketList = array();
    
                $query = "SELECT * FROM tickets t
                INNER JOIN bills b ON t.idBill=b.idBill
                INNER JOIN users u ON u.idUser=b.idUser
                INNER JOIN seats se ON t.seat=se.idSeat
                INNER JOIN shows s  ON t.idShow=s.idShow
                INNER JOIN movies m ON s.idMovie = m.idMovie 
                INNER JOIN rooms r ON s.idRoom = r.idRoom 
                INNER JOIN cinemas c ON r.idCinema = c.idCinema
                WHERE t.idShow=:idShow";

                $this->connection = Connection::getInstance();
                $parameters["idShow"]=$idShow;
    
                $result = $this->connection->execute($query,$parameters);
              
                if($result){
                    foreach($result as $value){
                        $mapping = $this->mapTicket($value);  
                        array_push($ticketList, $mapping);
                    }
                    return $ticketList;
                }
                else{
                    return null;
                }
                
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }

        } */

        public function search($idTicket){
            try
            {
                $query = "SELECT * FROM tickets WHERE idTicket= :idTicket";
                $parameters["idTicket"] = $idTicket;
    
                $this->connection = Connection::getInstance();
    
                $result = $this->connection->execute($query, $parameters);
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }
    
            if(!empty($result)){
                return $this->mapTicket($result[0]);
            }else{
                return null;
            }
        }

        /*
        public function remove($idTicket){
            try{
            $query="DELETE FROM tickets WHERE idTicket=:idTicket";
            $this->connection = Connection::getInstance();
            $parameters['idTicket']=$idTicket;
            $rowCant=$this->connection->executeNonQuery($query,$parameters);
            return $rowCant;
            }   
            catch(\PDOException $ex)
            {
            throw $ex;
            } 
        }*/


        /*
        public function update($ticket){
            try
            {   
                $query = "UPDATE tickets set idBill=:idBill , seat=:seat, idShow=:idShow , priceTicket=:priceTicket , qrCode=:qrCode WHERE idTicket=:idTicket";

                $this->connection = Connection::getInstance();
                $parameters['idShow']=$ticket->getShow()->getIdShow();

                $parameters['seat']=$ticket->getSeat();
                $parameters['idBill']=$ticket->getBill()->getIdBill();
                $parameters['priceTicket']=$ticket->getPrice();
                $parameters['qrCode']=$ticket->getQrCode();
                $parameters['idTicket']=$ticket->getIdTicket();

                

                $rowCant=$this->connection->executeNonQuery($query,$parameters);
                return $rowCant;
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }
        }*/


        /* Retorna el total de tickets vendidos para una fecha en un cine */
        public function ticketsByCinemaByDate($idCinema, $dateTime){
            try
            {   
                $query = "SELECT sum(b.tickets) FROM bills AS b
                            JOIN (SELECT * FROM tickets GROUP BY idBill) AS t
                            ON b.idBill = t.idBill
                            JOIN creditCardPayments AS ccp
                            ON b.codePayment = ccp.idCreditCardPayment
                            JOIN shows AS s
                            ON t.idShow = s.idShow
                            JOIN rooms AS r
                            ON s.idRoom = r.idRoom 
                            WHERE DATEDIFF(s.dateTime, :dateTime) = 0 AND r.idCinema = :idCinema";
                
                $parameters["idCinema"] = $idCinema;
                $parameters["dateTime"] = $dateTime;
                
                $this->connection = Connection::getInstance();
                
                $result = $this->connection->execute($query, $parameters);
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }

            if($result[0][0]){
                return $result[0][0];                
            }
            else{
                return 0;
            }      
        }    
        
        
        /* Retorna el total de tickets vendidos para un mes de este año en un cine*/
        public function ticketsByCinemaByMonth($idCinema, $month){
            try
            {   
                $query = "SELECT sum(b.tickets) FROM bills AS b
                            JOIN (SELECT * FROM tickets GROUP BY idBill) AS t
                            ON b.idBill = t.idBill
                            JOIN creditCardPayments AS ccp
                            ON b.codePayment = ccp.idCreditCardPayment
                            JOIN shows AS s
                            ON t.idShow = s.idShow
                            JOIN rooms AS r
                            ON s.idRoom = r.idRoom
                            WHERE MONTH(s.dateTime) = :month AND YEAR(s.dateTime) =  YEAR(NOW()) AND r.idCinema = :idCinema";
                
                $parameters["idCinema"] = $idCinema;
                $parameters["month"] = $month;


                $this->connection = Connection::getInstance();
                
                $result = $this->connection->execute($query, $parameters);
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }
              
            if($result[0][0]){
                return $result[0][0];                
            }
            else{
                return 0;
            }
        }


        /* Retorna el total de tickets vendidos para un año en un cine*/
        public function ticketsByCinemaByYear($idCinema, $year){
            try
            {   
                $query = "SELECT sum(b.tickets) FROM bills AS b
                            JOIN (SELECT * FROM tickets GROUP BY idBill) AS t
                            ON b.idBill = t.idBill
                            JOIN creditCardPayments AS ccp
                            ON b.codePayment = ccp.idCreditCardPayment
                            JOIN shows AS s
                            ON t.idShow = s.idShow
                            JOIN rooms AS r
                            ON s.idRoom = r.idRoom
                            WHERE YEAR(s.dateTime) = :year AND r.idCinema = :idCinema";
                
                $parameters["idCinema"] = $idCinema;
                $parameters["year"] = $year;
                
                $this->connection = Connection::getInstance();
                
                $result = $this->connection->execute($query, $parameters);
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }

            if($result[0][0]){
                return $result[0][0];                
            }
            else{
                return 0;
            }
        }


        /* Retorna el total de tickets vendidos por turno para una fecha en un cine */
        public function ticketsByCinemaByShiftByDate($idCinema, $shift, $date){
            try
            {   
                $query = "SELECT sum(b.tickets) FROM bills AS b
                            JOIN (SELECT * FROM tickets GROUP BY idBill) AS t
                            ON b.idBill = t.idBill
                            JOIN creditcardpayments AS ccp
                            ON b.codePayment = ccp.idCreditCardPayment
                            JOIN shows AS s
                            ON t.idShow = s.idShow
                            JOIN rooms AS r
                            ON s.idRoom = r.idRoom
                            WHERE DATEDIFF(s.dateTime, :date) = 0  AND r.idCinema = :idCinema AND s.shift = :shift";

                $parameters["idCinema"] = $idCinema;
                $parameters["shift"] = $shift;
                $parameters["date"] = $date;
                
                $this->connection = Connection::getInstance();
                
                $result = $this->connection->execute($query, $parameters);
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }

            if($result[0][0]){
                return $result[0][0];                
            }
            else{
                return 0;
            }        
        }


        /* Retorna el total de tickets vendidos por turno para un mes de este año en un cine */
        public function ticketsByCinemaByShiftByMonth($idCinema, $shift, $month){
            try
            {   
                $query = "SELECT sum(b.tickets) FROM bills AS b
                            JOIN (SELECT * FROM tickets GROUP BY idBill) AS t
                            ON b.idBill = t.idBill
                            JOIN creditcardpayments AS ccp
                            ON b.codePayment = ccp.idCreditCardPayment
                            JOIN shows AS s
                            ON t.idShow = s.idShow
                            JOIN rooms AS r
                            ON s.idRoom = r.idRoom
                            WHERE MONTH(s.dateTime) = :month AND YEAR(s.dateTime) =  YEAR(NOW())  AND r.idCinema = :idCinema AND s.shift = :shift";

                $parameters["idCinema"] = $idCinema;
                $parameters["shift"] = $shift;
                $parameters["month"] = $month;
                
                $this->connection = Connection::getInstance();
                
                $result = $this->connection->execute($query, $parameters);
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }

            if($result[0][0]){
                return $result[0][0];                
            }
            else{
                return 0;
            }        
        }


        /* Retorna el total de tickets vendidos por turno para un año en un cine */
        public function ticketsByCinemaByShiftByYear($idCinema, $shift, $year){
            try
            {   
                $query = "SELECT sum(b.tickets) FROM bills AS b
                            JOIN (SELECT * FROM tickets GROUP BY idBill) AS t
                            ON b.idBill = t.idBill
                            JOIN creditcardpayments AS ccp
                            ON b.codePayment = ccp.idCreditCardPayment
                            JOIN shows AS s
                            ON t.idShow = s.idShow
                            JOIN rooms AS r
                            ON s.idRoom = r.idRoom
                            WHERE YEAR(s.dateTime) = :year AND YEAR(s.dateTime) =  YEAR(NOW())  AND r.idCinema = :idCinema AND s.shift = :shift";

                $parameters["idCinema"] = $idCinema;
                $parameters["shift"] = $shift;
                $parameters["year"] = $year;
                
                $this->connection = Connection::getInstance();
                
                $result = $this->connection->execute($query, $parameters);
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }

            if($result[0][0]){
                return $result[0][0];                
            }
            else{
                return 0;
            }        
        }


        /* Retorna el total de tickets vendidos para una película en un cine */ 
        public function ticketsByCinemaByMovie($idCinema, $idMovie){
            try
            {   
                $query = "SELECT sum(b.tickets) FROM bills AS b
                            JOIN (SELECT * FROM tickets GROUP BY idBill) AS t
                            ON b.idBill = t.idBill
                            JOIN shows AS s
                            ON t.idShow = s.idShow
                            JOIN rooms AS r
                            ON s.idRoom = r.idRoom
                            WHERE s.idMovie = :idMovie AND r.idCinema = :idCinema";

                $parameters["idCinema"] = $idCinema;
                $parameters["idMovie"] = $idMovie;
                
                $this->connection = Connection::getInstance();
                
                $result = $this->connection->execute($query, $parameters);
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }

            if($result[0][0]){
                return $result[0][0];                
            }
            else{
                return 0;
            }           
        }

        /* Retorna el total de tickets vendidos para una película en un turno en un cine */ 
        public function ticketsByCinemaByMovieByShift($idCinema, $shift, $idMovie){
            try
            {   
                $query = "SELECT sum(b.tickets) FROM bills AS b
                            JOIN (SELECT * FROM tickets GROUP BY idBill) AS t
                            ON b.idBill = t.idBill
                            JOIN shows AS s
                            ON t.idShow = s.idShow
                            JOIN rooms AS r
                            ON s.idRoom = r.idRoom
                            WHERE s.idMovie = :idMovie AND r.idCinema = :idCinema AND s.shift = :shift";

                $parameters["idCinema"] = $idCinema;
                $parameters["shift"] = $shift;
                $parameters["idMovie"] = $idMovie;
                
                $this->connection = Connection::getInstance();
                
                $result = $this->connection->execute($query, $parameters);
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }

            if($result[0][0]){
                return $result[0][0];                
            }
            else{
                return 0;
            }           
        }

        
        /* Retorna el total de tickets vendidos para una función */ /*
        public function ticketsByshow($idShow){
            try
            {   
                $query = "SELECT sum(b.tickets) FROM bills AS b
                            JOIN tickets AS t
                            ON b.idBill = t.idBil.
                            WHERE t.idShow = :idShow";

                $parameters["idShow"] = $idShow;
                
                $this->connection = Connection::getInstance();
                
                $result = $this->connection->execute($query, $parameters);
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }

            return $result;        
        }
        /*

        
  
        

            



        
        /* Retorna el total de dinero recaudado en una fecha en un cine*/ 
        public function cashByCinemaByDate($idCinema, $date){
            try
            {   
                $query = "SELECT sum(ccp.total) FROM bills AS b
                            JOIN (SELECT * FROM tickets GROUP BY idBill) as t
                            ON b.idBill = t.idBill
                            JOIN creditCardPayments AS ccp
                            ON b.codePayment = ccp.idCreditCardPayment
                            JOIN shows AS s
                            ON t.idShow = s.idShow
                            JOIN rooms AS r
                            ON s.idRoom = r.idRoom
                            WHERE DATEDIFF(ccp.datePayment, :date) = 0 AND r.idCinema = :idCinema";
                
                
                $parameters["idCinema"] = $idCinema;
                $parameters["date"] = $date;
                
                $this->connection = Connection::getInstance();
                
                $result = $this->connection->execute($query, $parameters);
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }

            
            if($result[0][0]){
                return $result[0][0];                
            }
            else{
                return 0;
            }       
        }        


        /* Retorna el total de dinero recaudado en un mes en un cine*/ 
        public function cashByCinemaByMonth($idCinema, $month){
            try
            {   
                $query = "SELECT sum(ccp.total) FROM bills AS b
                            JOIN (SELECT * FROM tickets GROUP BY idBill) AS t
                            ON b.idBill = t.idBill
                            JOIN creditCardPayments AS ccp
                            ON b.codePayment = ccp.idCreditCardPayment
                            JOIN shows AS s
                            ON t.idShow = s.idShow
                            JOIN rooms AS r
                            ON s.idRoom = r.idRoom 
                            WHERE MONTH(ccp.datePayment) = :month AND YEAR(ccp.datePayment) =  YEAR(NOW()) AND r.idCinema = :idCinema";

                $parameters["idCinema"] = $idCinema;
                $parameters["month"] = $month;

                $this->connection = Connection::getInstance();
                
                $result = $this->connection->execute($query, $parameters);
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }

            if($result[0][0]){
                return $result[0][0];                
            }
            else{
                return 0;
            }
        }


        /* Retorna el total de dinero recaudado en un año en un cine*/
        public function cashByCinemaByYear($idCinema, $year){
            try
            {   
                $query = "SELECT sum(ccp.total) FROM bills AS b
                            JOIN (SELECT * FROM tickets GROUP BY idBill) AS t
                            ON b.idBill = t.idBill
                            JOIN creditCardPayments AS ccp
                            ON b.codePayment = ccp.idCreditCardPayment
                            JOIN shows AS s
                            ON t.idShow = s.idShow
                            JOIN rooms AS r
                            ON s.idRoom = r.idRoom 
                            WHERE YEAR(ccp.datePayment) = :year AND r.idCinema = :idCinema";

                $parameters["idCinema"] = $idCinema;
                $parameters["year"] = $year;
                
                $this->connection = Connection::getInstance();
                
                $result = $this->connection->execute($query, $parameters);
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }

            if($result[0][0]){
                return $result[0][0];   
            }
            else{
                return 0;
            }
        }


        /* retorna historial de tickets comprados del cliente, solo datos q se muestran en profile */
        public function getTicketsByClient($idUser){
            try
            {   
                $query = "SELECT bills.tickets, bills.totalPrice, tickets.idTicket, shows.dateTime, cinemas.name, movies.title FROM bills
                            INNER JOIN tickets ON tickets.idBill = bills.idBill
                            INNER JOIN shows ON tickets.idShow = shows.idShow
                            INNER JOIN movies ON shows.idMovie = movies.idMovie
                            INNER JOIN rooms ON shows.idRoom = rooms.idRoom
                            INNER JOIN cinemas ON rooms.idCinema = cinemas.idCinema
                            WHERE idUser = $idUser
                            GROUP BY bills.idBill";
                          
                
                $this->connection = Connection::getInstance();
                
                $result = $this->connection->execute($query);
                $history= array();

                if($result){
                    foreach($result as $r){
                        $results = $this->mapHistory($r);
                        array_push($history, $results);
                    }
                }
            }
            catch(\PDOException $ex)
            {
                throw $ex;
            }

            return $history;        
        }


        /* mapea resumen de historial de compras de cliente */
        protected function mapHistory($value){
            $ticket = new Ticket();
            $bill = new Bill();
            $show = new Show();
            $room = new Room();
            $cinema = new Cinema();
            $movie = new Movie();

            $ticket->setIdTicket($value["idTicket"]);
            $bill->setTickets($value["tickets"]);
            $bill->setTotalPrice($value["totalPrice"]);
            $show->setDateTime($value["dateTime"]);
            $cinema->setName($value["name"]);
            $movie->setTitle($value["title"]);
            
            $room->setCinema($cinema);
            $show->setRoom($room);
            $show->setMovie($movie);
            $ticket->setShow($show);
            $ticket->setBill($bill);

            return $ticket;

        }



        protected function mapTicket($value){
            $ticket=new Ticket();
            $ticket->setIdTicket($value["idTicket"]);
            $ticket->setBill($this->mapBill($value));
            $ticket->setSeat($this->mapSeat($value));
            $ticket->setShow($this->mapShow($value));
            $ticket->setPrice($value["priceTicket"]);

            return $ticket;

        }


        protected function mapShow($value){
        
            $show=new Show();
            $show->setIdShow($value["idShow"]);
            $show->setRoom($this->mapRoom($value));
            $show->setMovie($this->mapMovie($value));
            $show->setDateTime($value["dateTime"]);
            $show->setShift($value["shift"]);
            $show->setRemainingTickets($value["remainingTickets"]);
            
            return $show;
        
        }

        protected function mapRoom($value){
        
            $room=new Room();
            $room->setIdRoom($value["idRoom"]);
            $room->setType($value["type"]);
            $room->setCapacity($value["capacity"]);
            $room->setColumns($value["roomcolumns"]);
            $room->setRows($value["roomrows"]);
            $room->setPrice($value["price"]);
            $room->setName($value["name_room"]);
            $room->setState($value["stateRoom"]);
            $room->setCinema($this->mapCinema($value));

            return $room;

            
        }

        protected function mapCinema($value){
            
            $cinema=new Cinema();
            $cinema->setIdCinema($value["idCinema"]);
            $cinema->setState($value["state"]);
            $cinema->setName($value["name"]);
            $cinema->setStreet($value["street"]);
            $cinema->setNumber($value["number"]);
            $cinema->setEmail($value["email"]);
            $cinema->setPhone($value["phone"]);
            $cinema->setPoster($value["poster"]);   

            return $cinema;
        }

        protected function mapMovie($p){
        
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

            return $movie;
        }

        protected function mapSeat($value){
            $seat=new Seat();
            $seat->setIdSeat($value["idSeat"]);
            $seat->setRow($value["rowSeat"]);
            $seat->setNumber($value["numberSeat"]);

            return $seat;
        }

        protected function mapBill($value){
            $bill= new Bill();
            $bill->setIdBill($value["idBill"]);
            $bill->setUser($this->mapUser($value));
            $bill->setTickets($value["tickets"]);
            $bill->setDate($value["date"]);
            $bill->setTotalPrice($value["totalPrice"]);
            $bill->setDiscount($value["discount"]);
            
            return $bill;

        }

        protected function mapUser($p){        
            
            $user = new User();
            $user->setIdUser($p["idUser"]);
            $user->setDni($p["dni"]);
            $user->setName($p["name"]);
            $user->setSurname($p["surname"]);
            $user->setStreet($p["street"]);
            $user->setNumber($p["number"]);
            $user->setEmail($p["email"]);
            $user->setPassword($p["password"]);

            return $user;
        }

            

    }