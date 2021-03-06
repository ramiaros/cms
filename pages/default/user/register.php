<?php
/**
 * @file register.php
 * @desc See readme
 */
if ( !loggedIn() && in('mobile') == null && Config::$verifyMobileOnRegistration ) {
	return move(Config::$mobileVerificationPage);
}

/**
 * Init
 */


?>
<script>
    localStorage.removeItem('mobile')
</script>

<div id="register-page" class="container py-3">
    <div class="card">
        <div class="card-body">

            <h1><?= loggedIn() ? tr([en=>"Profile Update", ko =>'회원 정보 수정']) : tr([en=>"User Registration", ko =>'회원 가입'])?></h1>
            <form id="register-form" onsubmit="return onRegisterFormSubmit()">
				<?php if (loggedIn()) { ?>
                <input type="hidden" name="session_id" value="<?=login('session_id')?>">
				<?php } else { ?>
                <input type="hidden" name="firebase_uid" value="">
                    <script>
                        $$(function() {
                            firebaseAuth(function(user) {
                                $('[name="firebase_uid"]').val(user.uid);
                            });
                        })
                    </script>
				<?php } ?>
				<? include 'form-profile-photo.php'?>


				<?php if ( login(SOCIAL_LOGIN) == null ) { ?>

                    <div class="email mt-3">
                        <label  class="form-label"><?=tr(emailAddress)?></label>
                        <input type="email" class="form-control" aria-describedby="emailHelp" name="user_email" value="<?=login('user_email')?>">
                        <small class="form-text text-muted"><?=tr(emailAddressDescription)?></small>
                    </div>
					<? if (!loggedIn()) { ?>
                        <div class="password mt-3">
                            <label class="form-label"><?=tr('password')?></label>
                            <input type="password" class="form-control" name="user_pass">
                        </div>
					<?}?>


				<?php } ?>


                <div class="mt-3">
                    <label for="fullname" class="form-label"><?=tr('name')?></label>
                    <input type="text" class="form-control" id="fullname" name="fullname" value="<?=login('fullname')?>">
                </div>

                <div class="mt-3">
                    <label class="form-label"><?=tr('nickname')?></label>
                    <input type="text" class="form-control" name="nickname"  value="<?=login('nickname')?>">
                </div>


				<?php if ( Config::$mobileRequired ) { ?>
                    <div class="mt-3">
                        <label class="form-label"><?=tr('mobileNo')?></label>
                        <div>
							<?php if ( loggedIn() ) echo login('mobile'); else {?>

								<?php if ( Config::$verifiedMobileOnly ) { ?>

                                    <div class="mobile"></div>
                                <input type="hidden" name="mobile" value="">
                                    <script>
                                        $$(function() {
                                            $('.mobile').text(localStorage.getItem('mobile'))
                                            $('[name="mobile"]').val(localStorage.getItem('mobile'))
                                        })
                                    </script>

								<?php } else { ?>
                                <input class="form-control" type="text" name="mobile" value="">
								<?php }?>


							<?php } ?>

                        </div>
                    </div>
				<?php } ?>

                <hr>
                <button class="btn btn-primary w-100" type="submit" role="submit"><?=tr([en=>'Register', ko=>'회원 가입'])?></button>
            </form>

            <div class="mt-3" role="loader" style="display: none;">
                <div class="d-flex justify-content-center">
                    <div class="spinner"></div>
                    <div class="ml-3"><?=tr([
							ko => '회원 가입 중입니다...',
							en => 'Please wait...'
						])?></div>
                </div>
            </div>

        </div>
    </div>

	<?php include widget('user.logged-with') ?>

</div>




