<main class="py-5">
          <div class="container background-pic" style="background-image:url('<?php echo IMG_PATH?>/backgrounds/karen-zhao-jLRIsfkWRGo-unsplash.jpg');">  
          <h2 class="page-title up2">Add Cinema</h2> <br>
               <form action="<?php echo FRONT_ROOT?>Cinema/add" class="center" method="post">
                         <div class="floating-label-form">
                              <div class="floating-label">
                                   <input type="text" maxlength="50" name="name" placeholder=" " class="floating-input" required>
                                   <span class="highlight"></span><label for="">Name</label>
                              </div>                         

                              <div class="floating-label">
                                   <input type="text" maxlength="50" name="street" placeholder=" " class="floating-input" required>
                                   <span class="highlight"></span><label for="">Street</label>
                              </div>

                              <div class="floating-label">
                                   <input type="number" max="100000" name="number" placeholder=" " class="floating-input" required>
                                   <span class="highlight"></span><label for="">Number</label>
                              </div>

                              <div class="floating-label">
                                   <input type="text" maxlength="50" name="city" placeholder=" " class="floating-input" required>
                                   <span class="highlight"></span><label for="">City</label>
                              </div>
                              <br>
                              <div class="floating-label">
                                   <input type="text" maxlength="50" name="country" placeholder=" " class="floating-input" required>
                                   <span class="highlight"></span><label for="">Country</label>
                              </div>
                              <div class="floating-label">
                                   <input type="number" maxlength="12"  name="phone" placeholder=" " class="floating-input" required>
                                   <span class="highlight"></span><label for="">Phone</label>
                              </div>

                              <div class="floating-label">
                                   <input type="email" maxlength="50" name="email" placeholder=" " class="floating-input" required>
                                   <span class="highlight"></span><label for="">Email</label>
                              </div>
                              <div class="floating-label">
                                   <input type="text" maxlength="1000" name="img" placeholder="http://www.example.com/image.jpg " class="floating-input f" >
                                   <span class="highlight"></span><label for="">Image url (Optional)</label>
                              </div>
                              <br><br>
                              <div class="floating-label">
                                   <span>&nbsp;</span>
                                   <button type="submit" name="" class="btn btn-primary ml-auto d-block">Add</button>
                              </div>
                              <br>
                             <?php if($this->msg != null){?> <!-- Si el cine ya existe muestra el mensaje -->
                                   <h4 class="msg"><?php  echo $this->msg;
                              } ?> </h4>
                         </div>            
               </form>
          </div>
</main>


