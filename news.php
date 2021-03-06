<?php
session_start();
if($_SESSION['news']||$_SESSION['privilege']){
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>CU NEWS Admin</title>
<!-- Bootstrap Core CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet" media="screen">
<!-- Morris Charts CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.8.1/bootstrap-table.min.css" rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<link href="./css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<style>
td div{
    max-height: 100px;
    overflow: scroll;
}
</style>
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" style='border-color:#111010; background:#001113;'role="navigation">
          <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">CU NEWS Admin</a>
            </div>
          <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav" style="margin:0.3%">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['name'] ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="./logout.php"><i class="fa fa-fw fa-power-off"></i>Log Out</a></li>
                    </ul>
                </li>
            </ul>
          <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li class="active"><a href="./news.php"><i class="fa fa-fw fa-newspaper-o"></i>News</a></li>
                    <li><a href="./privilege.php"><i class="fa fa-fw fa-gift"></i>Privilege</a></li>
                    <li><a href="./createNews.php"><i class="fa fa-fw fa-pencil-square-o"></i>Create News</a></li>
                    <li><a href="./createPrivilege.php"><i class="fa fa-fw fa-star"></i>Create Privilege</a></li>
                    <li><a href="./logout.php"><i class="fa fa-fw fa-sign-out"></i>Logout</a></li>
                </ul>
            </div>
          <!-- /.navbar-collapse -->
        </nav>
    </div>
    <div id="page-wrapper">
      
        <div class="container-fluid">
        
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12" style="margin-top:1%">
                    <h1 class="page-header">News <small>ข้อมูลข่าว</small></h1>
                </div>
            </div>
            <!-- /.row -->
        
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-info alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="fa fa-info-circle"></i> การแก้ไขหรือลบข่าวถือเป็นความรับผิดชอบของหน่วยงานที่กระทำ<br/>
                        <i class="fa fa-info-circle"></i> โปรด Logout ทุกครั้งหลังใช้งานเพื่อป้องกันปัญหาบุคคลไม่ที่ไม่ได้รับอนุญาติเข้าถึงระบบ
                    </div>
                </div>
            </div>
            <!-- /.row -->
        
            <table
            id="table"
            data-toggle="table"
            data-search="true"
            data-show-refresh="true"
            data-show-toggle="true"
            data-show-columns="true"
            data-mobile-responsive="true"
            data-sortable="true"
            data-pagination="true"
            data-page-size='50'
            data-url="./cunews/totable.php"
            >
            <thead>
                <tr>
                    <th data-formatter='status' data-align='center'>สถานะ</th>
                    <th data-field='NEWS_IMG' data-formatter='newsImg'>รูปภาพ</th>
                    <th data-field='NEWS_TITLE' data-formatter='overflowScroll'>พาดหัวข่าว</th>
                    <th data-field='NEWS_MSG' data-formatter='overflowScroll'>เนื้อข่าว</th>
                    <th data-field='NEWS_LOCATION' data-formatter='overflowScroll'>สถานที่</th>
                    <th data-field='EVENT_START' data-formatter='timeConverter'>เวลาเริ่ม</th>
                    <th data-field='EVENT_END' data-formatter='timeConverter'>เวลาสิ้นสุด</th>
                    <th data-field='MOD' data-formatter='overflowScroll'>ผู้ดูแล</th>
                    <th data-field='NEWS_TS' data-visible='false' data-formatter='timeConverter'>วันที่สร้างข่าว</th>
                    <th data-field='POST_TS' data-visible='false' data-formatter='timeConverter'>แก้ไขครั้งล่าสุด</th>
                    <th data-formatter='modify' data-align='center' data-events='operateEvents'>แก้ไข</th>
                    <th data-formatter='del' data-align='center' data-events='operateEvents'>ลบ</th>
                </tr>
            </thead>
            <tbody></tbody>
            </table>

            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form id='myForm' name="myForm" data-toggle="validator" action="input_to_news.php" method="POST" enctype="multipart/form-data">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">แก้ไขข่าว</h4>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="del" value="false" hidden>
                                <input id='editNewsTS' type="text" name="newsTS" hidden>
                                <input id='editPostTS' type="text" name="postTS" hidden>
                                <div class="form-group">
                                    <label class="control-label">หัวข้อข่าว</label>
                                    <input id='editTitle' name='title' type="topic" size='50' class="form-control" placeholder="Enter title" readonly required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">เนื้อหาข่าว</label>
                                    <textarea id='editMessage' name='message' class="form-control" rows="10" placeholder='Enter content' required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>เวลาเริ่มกิจกรรม</label>
                                    <div class="input-group date form_datetime col-md-5" data-date-format="dd MM yyyy - HH:ii" data-link-field="dtp_input1">
                                        <input name='eventStart' class="form-control" size="16" type="text" placeholder='Click to select start time' readonly>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>เวลาสิ้นสุดกิจกรรม</label>
                                    <div class="input-group date form_datetime col-md-5" data-date-format="dd MM yyyy - HH:ii" data-link-field="dtp_input1">
                                        <input name='eventEnd' class="form-control" size="16" type="text" placeholder='Click to select end time' readonly>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>สถานที่จัดกิจกรรม</label>
                                    <div class="input-group col-md-5">
                                        <input id='editLocation' name='location' class="form-control" type="text" placeholder='Enter location'>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button id='change' type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
<script>
    function newsImg(value){
        if( value ) {
            return "<img src="+value+" width='100' height='100' />";
        }
        return '-';
    }
    function timeConverter(UNIX_timestamp){
        var a = new Date(UNIX_timestamp*1000);
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        var year = a.getFullYear();
        var month = months[a.getMonth()];
        var date = a.getDate();
        var hour = a.getHours() < 10 ? hour = "0" + a.getHours() : hour = a.getHours();
        var min = a.getMinutes() < 10 ? min = "0" + a.getMinutes() : min = a.getMinutes();
        var sec = a.getSeconds() < 10 ? sec = "0" + a.getSeconds() : sec = a.getSeconds();
      
        var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
        return time;
    }
    function overflowScroll(value){
        if( value ){
            return "<div>"+value+"</div>";
        }
        return '-';
    }
    function modify(){
        return '<a href="javascript:void(0)" class="edit" title="edit"><i class="fa fa-pencil fa-fw"></i> Edit</a>';
    }
    function del(){
        return '<a href="javascript:void(0)" class="del" title="del"> <i class="btn btn-danger"><i class="fa fa-trash-o fa-lg"></i> Delete</i></a>';
    }
    function status(){
        return '<i class="fa fa-pencil-square-o"></i>';
    }
    window.operateEvents = {
        'click .del': function (e, value, row, index) {
            if(confirm('Are you sure to delete topic '+ row.NEWS_TITLE) == true){
                function newDoc() {
                    var redirect = 'input_to_news.php';
                    $.extend(
                    {
                        redirectPost: function(location, args)
                        {
                            var form = '';
                            $.each( args, function( key, value ) {
                                form += '<input type="hidden" name="'+key+'" value="'+value+'">';
                            });
                            $('<form action="'+location+'" method="POST">'+form+'</form>').appendTo('body').submit();
                        }
                    });
                    $.redirectPost(redirect, { del : "true", postTS : row.POST_TS });
                    // jquery extend function
                }
                newDoc();
            }
        },
        'click .edit': function (e, value, row, index) {
            $('#editNewsTS').val(row.NEWS_TS);
            $('#editPostTS').val(row.POST_TS);
            $('#editTitle').val(row.NEWS_TITLE);
            $('#editMessage').html(row.NEWS_MSG);
            $('#editLocation').val(row.NEWS_LOCATION);
            $('#myModal').modal();
            $('#change').click(function(){
                $('#myForm').submit();
            });
        }
    };
</script>
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.8.1/bootstrap-table.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.8.1/extensions/export/bootstrap-table-export.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.8.1/locale/bootstrap-table-th-TH.min.js"></script>
<script src="./js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script src="./js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script type="text/javascript">
    $(".form_datetime").datetimepicker({
        format: 'yyyy/mm/dd hh:ii',
        autoclose: 1
    });
</script>
</body>
</html>
<?php
}else{
header( "location: ./login.php" );
exit(0);
}
?>