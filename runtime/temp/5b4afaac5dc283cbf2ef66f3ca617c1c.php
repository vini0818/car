<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:74:"D:\www\service\wx\admin\public/../application/admin\view\warning\edit.html";i:1524220087;s:66:"D:\www\service\wx\admin\application\admin\view\layout\default.html";i:1524116523;s:63:"D:\www\service\wx\admin\application\admin\view\common\meta.html";i:1524116522;s:65:"D:\www\service\wx\admin\application\admin\view\common\script.html";i:1524126691;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <form id="edit-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">

    <div class="form-group">
        <label for="c-type" class="control-label col-xs-12 col-sm-2"><?php echo __('Type'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-type" class="form-control" name="row[type]" type="text" value="<?php echo $row['type']; ?>" readonly="true">
        </div>
    </div>
    <div class="form-group">
        <label for="c-title" class="control-label col-xs-12 col-sm-2"><?php echo __('Title'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-title" data-rule="required" class="form-control" name="row[title]" type="text" value="<?php echo $row['title']; ?>" readonly="true">
        </div>
    </div>
    <div class="form-group">
        <label for="c-content" class="control-label col-xs-12 col-sm-2"><?php echo __('Content'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea id="c-content" data-rule="required" class="form-control editor" rows="5" name="row[content]" cols="50" readonly="true"><?php echo $row['content']; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="c-is_read" class="control-label col-xs-12 col-sm-2"><?php echo __('Is_read'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-is_read" class="form-control" name="row[is_read]" type="text" value="<?php echo $row['is_read']; ?>" readonly="true">
        </div>
    </div>
    <div class="form-group">
        <label for="c-read_time" class="control-label col-xs-12 col-sm-2"><?php echo __('Read_time'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-read_time" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="row[read_time]" type="text" value="<?php echo $row['read_time']; ?>" readonly="true">
        </div>
    </div>
    <div class="form-group">
        <label for="c-memo" class="control-label col-xs-12 col-sm-2"><?php echo __('Memo'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea id="c-memo" class="form-control " rows="5" name="row[memo]" cols="50"><?php echo $row['memo']; ?></textarea>
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/socket.io.js"></script>
<script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>