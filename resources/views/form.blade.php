<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.1.1.min.js"></script>
<form action="">
    {!! csrf_field() !!}
    <input type="text" name="name">
</form>
<script type="text/javascript">
    var token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjIsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvbGFyYS9wdWJsaWMvaW5kZXgucGhwL2FwaS92MS91c2VyL2xvZ2luIiwiaWF0IjoxNTAwNjA5NDAyLCJleHAiOjE1MDA2MTMwMDIsIm5iZiI6MTUwMDYwOTQwMiwianRpIjoiNmpRblNSQldIWE9LY1Z4MiJ9.iyfKSI24HXXdJ_a7wmXxQVt_hSkQoV-1Nn2UiVl0FA4';
    $.ajax({
        url: "http://localhost/lara/public/index.php/api/v1/user/auth",
        data: {
            'obj': 'attr',
            'name': 'zhangsan'
        },
        type: "post",
        beforeSend: function(xhr){
            xhr.setRequestHeader('Authorization', 'Bearer ' + token);
        },
        success: function(ret) {
            console.log(ret);
        }
    });
</script>