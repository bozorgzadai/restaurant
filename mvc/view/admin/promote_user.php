<form action="/notes-v3/admin/promote" method="post">
  <div class="ltr tal">
    <input name="userId" type="text" placeholder="User ID" id="userId"><br><br>
    <input name="access" style="width: 300px" type="text" id="access" placeholder="Access Names ( Seperated By , )"><br>
    <br>
    <button type="submit">Promote</button>
  </div>
</form>

<script>
  $(function(){
    $('#userId').on('keyup', function(){
      var value = $(this).val();
      $.ajax('/notes-v3/admin/getUserAccess/' + value, {
        dataType: 'json',
        success: function(data){
          var access = data.access.replaceAll(/\|/g, ',', data.access);
          access = access.substring(1, access.length - 1);
          $("#access").val(access);
        }
      });
    });
  });
</script>