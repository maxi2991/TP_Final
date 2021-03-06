<div class="bordo">
  <div class="container ctr"> 
                       <!------------------------------------------------ CONTENT ------------------------------------------------>
    <h2 class="page-title up2 www">Billboard</h2>
    <div class="bkg">
      <main class="container clear " >          
                <span></span> <!-- no borrar ! -->
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <!------------------------------------------------ FORM - GENRE/DATE SELECTOR ------------------------------------------------>
                <div class="hoc floating-label-form up mrg_btm">
                  <div class="mrg_btm">
                    <form action="<?php echo FRONT_ROOT?>Show/filterShow" class="center span hoc " method="post">
                      <div class="floating-label cardStyle ">
                        <input type="date" name="date" value="" placeholder="" class="floating-input overf" min="<?php echo date('Y-m-d');?>">
                        <span class="highlight"></span><label for="">Date</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                      

                      <select name="idGenre" class=" selection" id="genre">
                        <?php if($actualGenre){?>
                          <option value="<?php echo $actualGenre->getId();?>" selected> <?php echo $actualGenre->getName();?> </option>
                        <?php } ?>
                        <option value=<?php echo $allGenre->getId();?> class=" selected"> <?php echo $allGenre->getName();?> </option>
              
                        <?php foreach($genreList as $genre) { ?>
                          <option value="<?php echo $genre->getId();?>">
                          <?php echo $genre->getName();?>
                          </option>
                        <?php } ?> 
                      </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 

                      
                        <button type="submit" name="" class="btn">Filter</button>
                      </div>
                    </form>
                  </div>
                </div> 

                        <!------------------------------------------------ GALLERY ------------------------------------------------>
        <div class=" padSides">

              <?php $indice=0;
                  foreach($movieList as $movie){
                      if($indice % 4 == 0){?>
                      
                        <!------------------------------------------------ PRIMER CUARTO ------------------------------------------->
        
                        <div class="one_quarter first mrg_btm up">
                          <div class="cardStyle ">

                          <button type="" class="notCollapsible"><?php if(strlen($movie->getTitle()) > 33){
                                                          $str1 = substr($movie->getTitle(), 0, 30) . '...';
                                                          echo $str1; ?><?php }else{ echo $movie->getTitle(); } ?></button>
                          
                            <div class="posterBillboard-hover-zoom posterBillboard-hover-zoom--slowmo">
                              <a href="<?php echo FRONT_ROOT?>Movie/showMovie/<?php echo $movie->getTmdbID()?>">
                                <img class="posterBillboard" src="<?php echo $movie->getPoster()?>" alt="<?php echo $movie->getTitle()?> movie poster">
                              </a>
                            </div>
          
                           <!-- <div class="one_half">
                              <span>
                                  <h4><?php /*echo $movie->getTitle()?></h4>
                              </span>
                              <span>
                                  <h4><?php echo $movie->getReleaseDate() */?></h4>
                              </span>
                            </div>-->
          
                            <div>
                              <button type="button" class="collapsible">Show List</button>
                              <div class="content1">
                                <?php if((isset($_SESSION)) && (isset($_SESSION["loggedUser"]) && $_SESSION["loggedUser"] != "admin@moviepass.com")){
                                        foreach($showList as $show){
                                          if($show->getMovie()->getTmdbId() == $movie->getTmdbId()){ ?>
                                            <a href="<?php echo FRONT_ROOT?>/Ticket/showPurchaseView/<?php echo $show->getIdShow()?>">
                                              <p class="p_orange">
                                                <?php if(strlen($show->getRoom()->getCinema()->getName()) > 13){
                                                      $str1 = substr($show->getRoom()->getCinema()->getName(), 0, 11) . '...';
                                                      echo $str1; ?></h6><?php }else{
                                                      echo $show->getRoom()->getCinema()->getName();}?>
                                              </p> <hr>
                                              <p class="p_white">
                                                <?php echo date('l d M - H:i', strtotime($show->getDateTime()))." hs ";?><i class="fa fa-ticket" style="font-size: 1.73em"></i>
                                              </p>
                                            </a><?php 
                                          }
                                        }
                                      }
                                      else{
                                        foreach($showList as $show){
                                          if($show->getMovie()->getTmdbId() == $movie->getTmdbId()){ ?>
                                              <p class="p_orange">
                                                <?php if(strlen($show->getRoom()->getCinema()->getName()) > 13){
                                                      $str1 = substr($show->getRoom()->getCinema()->getName(), 0, 11) . '...';
                                                      echo $str1; ?></h6><?php }else{
                                                      echo $show->getRoom()->getCinema()->getName();}?>
                                              </p> <hr>
                                              <p class="p_white">
                                                <?php echo date('l d M - H:i', strtotime($show->getDateTime()))." hs ";?><i class="fa fa-ticket" style="font-size: 1.73em"></i>
                                                <?php echo "Login to buy tickets" ?>
                                              </p>
                                            <?php 
                                          }
                                        }
                                      }
                                       ?> 
                              </div> 
                            </div>

                          </div>
                        </div>
        
                          <?php 
                      } else { ?>
        
                              <!------------------------------------------------ LOS OTROS CUARTOS ---------------------------------->

                        <div class="one_quarter mrg_btm up">
                          <div class="cardStyle ">
                            
                          <button type="" class="notCollapsible"><?php echo $movie->getTitle()?></button>

                            <div class="posterBillboard-hover-zoom posterBillboard-hover-zoom--slowmo">
                            <a href="<?php echo FRONT_ROOT?>Movie/showMovie/<?php echo $movie->getTmdbID()?>">
                              <img class="posterBillboard" src="<?php echo $movie->getPoster()?>" alt="<?php echo $movie->getTitle()?> movie poster">
                            </a>
                            </div>
            
                             <!-- <div class="one_half">
                              <span>
                                  <h4><?php /*echo $movie->getTitle()?></h4>
                              </span>
                              <span>
                                  <h4><?php echo $movie->getReleaseDate() */?></h4>
                              </span>
                            </div>-->
            
                            <div>
                              <button type="button" class="collapsible">Show List</button>
                              <div class="content1">
                              <?php if((isset($_SESSION)) && (isset($_SESSION["loggedUser"]) && $_SESSION["loggedUser"] != "admin@moviepass.com")){
                                      foreach($showList as $show){
                                          if($show->getMovie()->getTmdbId() == $movie->getTmdbId()){ ?>
                                            <a href="<?php echo FRONT_ROOT?>/Ticket/showPurchaseView/<?php echo $show->getIdShow()?>">
                                              <p class="p_orange">
                                                <?php if(strlen($show->getRoom()->getCinema()->getName()) > 13){
                                                      $str1 = substr($show->getRoom()->getCinema()->getName(), 0, 11) . '...';
                                                      echo $str1; ?><?php }else{
                                                      echo $show->getRoom()->getCinema()->getName();}?>
                                              </p> <hr>
                                              <p class="p_white">
                                                <?php echo date('l d M - H:i', strtotime($show->getDateTime()))." hs ";?>
                                                <i class="fa fa-ticket" style="font-size: 1.73em"></i>
                                              </p>
                                            </a><?php 
                                          }
                                      }
                                    }
                                    else{
                                      foreach($showList as $show){
                                        if($show->getMovie()->getTmdbId() == $movie->getTmdbId()){ ?>
                                            <p class="p_orange">
                                              <?php if(strlen($show->getRoom()->getCinema()->getName()) > 13){
                                                    $str1 = substr($show->getRoom()->getCinema()->getName(), 0, 11) . '...';
                                                    echo $str1; ?><?php }else{
                                                    echo $show->getRoom()->getCinema()->getName();}?>
                                            </p> <hr>
                                            <p class="p_white">
                                              <?php echo date('l d M - H:i', strtotime($show->getDateTime()))." hs ";?>
                                              <i class="fa fa-ticket" style="font-size: 1.73em"></i>
                                              <?php echo "Login to buy tickets" ?>
                                            </p>
                                          <?php 
                                        }
                                      }
                                    }
                                  ?> 
                              </div> 
                            </div>
                            </div>
                          </div>
                        <?php 
                      } $indice++;
                    }  ?>
                                 <!------------------------------------------- Error message ---------------------------------------------------------------->
                    <div class="hoc" margin-left="40%"><br>
                      <?php if($this->msg != null){?> 
                            <center><h4 class="msg"><?php  echo $this->msg;
                        } ?> </h4></center>
                    </div>      

                                  <!----------------------------------------------------------------------------------------------------------->
        </div>     
      </main>
    </div>
  </div>
</div>


<script>
var coll = document.getElementsByClassName("collapsible");
var i;
for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    }
  });
}</script>
