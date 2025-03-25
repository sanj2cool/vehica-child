<?php
//get_template_part('templates/chat/chat');
?>

<script>
  jQuery(document).ready(function($) {
    // Function to check if the screen width is greater than a certain value (e.g., 768px for tablets)
    function isDesktop() {
      return window.innerWidth > 767;
      console.log(window.innerWidth);
    }

    if (isDesktop() && !localStorage.getItem('popupShown')) {
      $("#overlay_pop, #popuponload").fadeIn();

      $(".close-btn, #overlay_pop").click(function() {
        $("#overlay_pop, #popuponload").fadeOut();
        localStorage.setItem('popupShown', 'true');
      });
    }
  });
  
  
// Check if the URL contains "action=create"
if (window.location.href.includes("action=create")) {
    document.addEventListener("mouseleave", function (event) {
        if (event.clientY < 10) {
            //document.getElementById("exitPopup").style.display = "block"; // Show popup
			
			let popup = document.querySelector(".cspopup");
            if (popup) {
                popup.click();
            }
			
        }
    });
}




</script>
<div id="exitPopup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 1px solid black;z-index:9999;">
    <p>Wait! Don't leave yet!</p>
	<button class="cspopup">button</button>
    <button onclick="document.getElementById('exitPopup').style.display='none'">Close</button>
</div>
<?php wp_footer(); ?>
</body>
</html>