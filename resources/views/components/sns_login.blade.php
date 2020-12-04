<div class="sns-link form-group row" style="align-items: center;">
    <label for="password" class="col-md-6 offset-md-3 col-form-label text-center mb-3">ソーシャルアカウントで登録</label>

    <div class="col-md-12 text-center">
        <!-- <ul>
            <li><a href=""><img src="/images/login/icon_twitter.png" alt="Twitterでログイン"></a></li>
            <li><a href=""><img src="/images/login/icon_facebook.png" alt="Facebookでログイン"></a></li>
        </ul> -->

        <a class="btn btn-link twitter-link" href="{{ route('users.login.twitter') }}">
            <!-- <img src="/images/login/icon_twitter.png" alt="Twitterでログイン"> -->
            <i class="fab fa-twitter"></i>
            <span class="twitter-link-text">Twitterでログイン</span>
        </a>
        <a class="btn btn-link facebook-link" href="{{ route('users.login.facebook') }}">
            <!-- <img src="/images/login/icon_facebook.png" alt="Facebookでログイン"> -->
            <i class="fab fa-facebook-f"></i>
            <span class="facebook-link-text">Facebookでログイン</span>
        </a>
    </div>
</div>