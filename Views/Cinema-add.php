<main class="py-5">
          <div class="container background-pic" style="background-image:url('<?php echo IMG_PATH?>/backgrounds/karen-zhao-jLRIsfkWRGo-unsplash.jpg');">  
          <h2 class="page-title up2">Add Cinema</h2> 
               <form action="<?php echo FRONT_ROOT?>Cinema/Add" method="post">
                         <div class="floating-label-form">
                              <div class="floating-label margin-left">
                                   <input type="text" name="name" placeholder=" " class="floating-input" required>
                                   <span class="highlight"></span><label for="">Cinema</label>
                              </div>                         

                              <div class="floating-label">
                                   <input type="text" name="street" placeholder=" " class="floating-input" required>
                                   <span class="highlight"></span><label for="">Street</label>
                              </div>

                              <div class="floating-label">
                                   <input type="number" name="number" placeholder=" " class="floating-input" required>
                                   <span class="highlight"></span><label for="">Number</label>
                              </div>

                              <div class="floating-label">
                                   <input type="number" name="phone" placeholder=" " class="floating-input" required>
                                   <span class="highlight"></span><label for="">Phone</label>
                              </div>

                              <div class="floating-label">
                                   <input type="email" name="email" placeholder=" " class="floating-input" required>
                                   <span class="highlight"></span><label for="">Email</label>
                              </div>
                              <div class="floating-label">
                                   <span>&nbsp;</span>
                                   <button type="submit" name="" class="btn btn-primary ml-auto d-block">Add</button>
                              </div>
                         </div>            
               </form>
          </div>
</main>

<?php include('footer.php') ?>
