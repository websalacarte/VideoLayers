<script src="js/poll.js" type="text/javascript"></script>
<script type="text/javascript">
window.onload = function(){list_nums();}
</script>
<div class="sidebar1">
   <ul class="nav">
      <li><a href="#" target="_blank">Video Lessons</a></li>
      <li><a href="#" target="_blank">Subscribe</a></li>
      <li><a href="#" target="_blank">Source Code</a></li>
      <li><a href="#" target="_blank">Forums</a></li>
      <li><a href="#">WTF?</a></li>
    </ul>
    <div style="padding:6px; text-align:center;"><strong>Your interests are:</strong></div>
    <div style="padding:6px;">
    <form id="my_form" style="padding:6px;"><label style="cursor:pointer;"><input type="radio" id="radio1" name="radio" value="1" />Film school</label><span id="count1" style="float:right; padding-right:5px;"></span>
  <br />
  <br />
  <label style="cursor:pointer;"><input type="radio" id="radio2" name="radio" value="2" />Business elearning</label><span id="count2" style="float:right; padding-right:5px;"></span>
  <br />
  <br />
  <label style="cursor:pointer;"><input type="radio" id="radio3" name="radio" value="3" />Social communities</label><span id="count3" style="float:right; padding-right:5px;"></span>
  <p class="submit" style="text-align:center;">
  <br />
  <button type="button" onclick="ajax_post();" onmouseout="list_nums();" value="Cast Vote">Cast Vote</button>
  </p>
  <p id="status"></p>
  </form>
  </div>
    &nbsp;<img src="images/sidebar_logo.png" alt="websalacarte" style="width:94%;"/>
</div>