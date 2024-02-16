document.addEventListener("DOMContentLoaded", function () {
  var footerNeve = document.querySelector(".footer-bottom");
  if (footerNeve) {
    footerNeve.remove();
  }

  var footerNeveMobie = document.querySelector(".footer-bottom-inner");
  if (footerNeveMobie) {
    footerNeveMobie.remove();
  }
});
