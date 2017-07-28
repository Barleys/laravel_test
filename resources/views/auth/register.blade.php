<form method="post" action="{{url('/auth/register')}}">
    <input type="text" name="name" placeholder="name">
    <input type="text" name="email" placeholder="email">
    <input type="password" name="password" placeholder="password">
    <input type="password" name="password_confirmation" placeholder="comfirm">
    <input type="submit" value="signup"/>
</form>