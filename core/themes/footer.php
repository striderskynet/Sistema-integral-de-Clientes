<!-- Footer -->
<footer class="text-center text-lg-start bg-light text-muted fixed-bottom">
  <section class="d-flex justify-content-center justify-content-lg-between p-1 border-bottom">
    <!--<div class="me-5 d-none d-lg-block">
          <span>Conectate con nosotros en las redes sociales:</span>
        </div>
        <div>
          <a href="" class="me-4 text-reset"><i class="fab fa-facebook-f"></i></a>
          <a href="" class="me-4 text-reset"><i class="fab fa-twitter"></i></a>
          <a href="" class="me-4 text-reset"><i class="fab fa-google"></i></a>
          <a href="" class="me-4 text-reset"><i class="fab fa-instagram"></i></a>
          <a href="" class="me-4 text-reset"><i class="fab fa-linkedin"></i></a>
          <a href="" class="me-4 text-reset"><i class="fab fa-github"></i></a>
        </div>-->
  </section>
  <div class="text-center p-2" style="background-color: rgba(0, 0, 0, 0.05);">&copy; 2022 Copyright -- <?php echo _VERSION ?> --
    <a class="text-reset fw-bold" href="https://github.com/striderskynet/ICC">GitHub / StriderSkynet</a>
  </div>
</footer>
</body>

<script type="text/javascript" src="./core/script.php?js=main"></script>
<?php
if ($theme_script) echo ("<script type=\"text/javascript\" src=\"./core/script.php?js=$theme_script\" ></script>");
?>
<script>
  set_position(<?php echo @$login ?>);
</script>

</html>