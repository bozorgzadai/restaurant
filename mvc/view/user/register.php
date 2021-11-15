<div class="tac" style="background-color: #F2F2F2; padding: 20px 0 20px 0; min-height: 330px;">
  <img src="<?=baseUrl()?>/image/notes.png"><br><br>

  <form action="<?=baseUrl();?>/user/register" method="post">
    <input type="text" class="ltr" placeholder="<?=_ph_email?>" name="email"><br>
    <br>
    <input type="password" class="ltr" placeholder="<?=_ph_password?>" name="password1"><br>
    <input type="password" class="ltr" placeholder="<?=_ph_confirm_password?>" name="password2"><br>
    <br>
    <input type="text" placeholder="<?=_ph_name?>" name="firstName"><br>
    <input type="text" placeholder="<?=_ph_family?>" name="family"><br>
    <input type="text" placeholder="<?=_ph_mobile?>" name="mobile"><br>
    <textarea id="addressArea" placeholder="<?=_ph_address?>" name="address"></textarea>
    <br>
    <br>
    <button type="submit" class="btn-blue"><?=_btn_register?></button>
  </form>
</div>