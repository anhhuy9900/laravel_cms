<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Admin </title>

    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{ asset('public/admin/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/font-awesome/4.2.0/css/font-awesome.min.css') }}" />

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="{{ asset('public/admin/css/daterangepicker.min.css') }}" />

    <!-- text fonts -->
    <link rel="stylesheet" href="{{ asset('public/admin/fonts/fonts.googleapis.com.css') }}" />

    <!-- colorbox styles -->
    <link rel="stylesheet" href="{{ asset('public/admin/css/colorbox.min.css') }}"  />

    <!-- ace styles -->
    <link rel="stylesheet" href="{{ asset('public/admin/css/ace-skins.min.css') }}" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="{{ asset('public/admin/css/ace.min.css') }}" class="ace-main-stylesheet" id="main-ace-style" />

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="{{ asset('public/admin/css/ace-part2.min.css') }}" class="ace-main-stylesheet" />
    <![endif]-->

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="{{ asset('public/admin/css/ace-ie.min.css') }}" />
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    <script src="{{ asset('public/admin/js/ace-extra.min.js') }}"></script>

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <!--[if lte IE 8]>
    <script src="{{ asset('public/admin/js/html5shiv.min.js') }}"></script>
    <script src="{{ asset('public/admin/js/respond.min.js') }}"></script>
    <![endif]-->

    <!--[if !IE]> -->
    <script src="{{ asset('public/admin/js/jquery.2.1.1.min.js') }}"></script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script src="{{ asset('public/admin/js/jquery.1.11.1.min.js') }}"></script>
    <![endif]-->

    <script type="text/javascript">
        var path_url_assset = '{{ asset('public/admin/') }}';
    </script>

</head>

<body class="no-skin">
<div id="navbar" class="navbar navbar-default">
    <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
    </script>

    <div class="navbar-container" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left">
            <a href="index.html" class="navbar-brand">
                <small>
                    <!--<i class="fa fa-leaf"></i>-->
                    BooCMS Admin
                </small>
            </a>
        </div>

        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">

                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <!--<img class="nav-user-photo" src="public/avatars/user.jpg" alt="Jason's Photo" />-->
						<span class="user-info">
							<small>Welcome,</small>
                            {{ 'Admin' }}
						</span>

                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <!--<li>
                            <a href="#">
                                <i class="ace-icon fa fa-cog"></i>
                                Settings
                            </a>
                        </li>
                        <li>
                            <a href="profile.html">
                                <i class="ace-icon fa fa-user"></i>
                                Profile
                            </a>
                        </li>

                        <li class="divider"></li>-->

                        <li>
                            <a href="{{ route('admin_logout') }}" title="Logout Account">
                                <i class="ace-icon fa fa-power-off"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div><!-- /.navbar-container -->
</div>

<div class="main-container" id="main-container">
    <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
    </script>

    <div id="sidebar" class="sidebar responsive">
        <script type="text/javascript">
            try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
        </script>

        <ul class="nav nav-list">
            <li class="active">
                <a href="#">
                    <i class="menu-icon fa fa-tachometer"></i>
                    <span class="menu-text"> Dashboard </span>
                </a>

                <b class="arrow"></b>
            </li>

            <li class="">
                <a href="javascript:;" class="dropdown-toggle">
                    <i class="menu-icon fa fa-list"></i>
							<span class="menu-text">
								Manage Modules
							</span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <!---Load list modules admin -->
                <!-- start: Main Menu -->
                {!! $left_menu !!}
                <!-- end: Main Menu -->

            </li>

        </ul><!-- /.nav-list -->

        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>

        <script type="text/javascript">
            try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
        </script>
    </div>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs" id="breadcrumbs">
                <script type="text/javascript">
                    try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                </script>

                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>
                    <li class="active">Dashboard</li>
                </ul><!-- /.breadcrumb -->

            </div>

            <div class="page-content">
                @yield('content')
            </div>

        </div>
    </div><!-- /.main-content -->

    <div class="footer">
        <div class="footer-inner">
            <div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Demo</span>
							Application &copy; 2013-2014
						</span>

                &nbsp; &nbsp;
						<span class="action-buttons">
							<a href="#">
                                <i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
                            </a>

							<a href="#">
                                <i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
                            </a>

							<a href="#">
                                <i class="ace-icon fa fa-rss-square orange bigger-150"></i>
                            </a>
						</span>
            </div>
        </div>
    </div>

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
</div><!-- /.main-container -->

<!-- start: Errors Messages-->
@include('admin.messages.errors')
<!-- end: Errors Messages -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='{{ asset('public/admin/js/jquery.min.js') }}'>"+"<"+"/script>");
</script>
<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='{{ asset('public/admin/js/jquery1x.min.js') }}'>"+"<"+"/script>");
</script>
<![endif]-->
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='{{ asset('public/admin/js/jquery.mobile.custom.min.js') }}'>"+"<"+"/script>");
</script>

<!-- bootstrap scripts -->
<script src="{{ asset('public/admin/js/bootstrap.min.js') }}"></script>

<!-- ckeditor -->
<script src="{{ asset('public/admin/js/ckeditor/ckeditor.js') }}"></script>

<!-- end ckeditor -->

<!--[if lte IE 8]>
<script src="{{ asset('public/admin/js/excanvas.min.js') }}"></script>
<![endif]-->
<script src="{{ asset('public/admin/js/jquery-ui.custom.min.js') }}"></script>
<script src="{{ asset('public/admin/js/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ asset('public/admin/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('public/admin/js/moment.min.js') }}"></script>
<script src="{{ asset('public/admin/js/daterangepicker.min.js') }}"></script>


<!-- ace scripts -->
<script src="{{ asset('public/admin/js/ace-elements.min.js') }}"></script>
<script src="{{ asset('public/admin/js/ace.min.js') }}"></script>

<!-- plupload scripts -->
<script src="{{ asset('public/admin/js/plupload/plupload.full.min.js') }}"></script>
<script src="{{ asset('public/admin/js/plupload/jquery.ui.plupload/jquery.ui.plupload.js') }}"></script>

<script src="{{ asset('public/admin/js/bootstrap-tag.min.js') }}"></script>

<!-- file js  plugin fancybox for image -->
<script src="{{ asset('public/admin/js/jquery.colorbox.min.js') }}"></script>

<!-- file js common for admin pages -->
<script src="{{ asset('public/admin/js/admin-main.js') }}"></script>

<!-- inline scripts related to this page -->
<script type="text/javascript">
    jQuery(function($) {
        // $('.easy-pie-chart.percentage').each(function(){
        //     var $box = $(this).closest('.infobox');
        //     var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
        //     var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
        //     var size = parseInt($(this).data('size')) || 50;
        //     $(this).easyPieChart({
        //         barColor: barColor,
        //         trackColor: trackColor,
        //         scaleColor: false,
        //         lineCap: 'butt',
        //         lineWidth: parseInt(size/10),
        //         animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
        //         size: size
        //     });
        // })

        $('.sparkline').each(function(){
            var $box = $(this).closest('.infobox');
            var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
            $(this).sparkline('html',
                    {
                        tagValuesAttribute:'data-values',
                        type: 'bar',
                        barColor: barColor ,
                        chartRangeMin:$(this).data('min') || 0
                    });
        });


        //flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
        //but sometimes it brings up errors with normal resize event handlers
        // $.resize.throttleWindow = false;

        var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
        // var data = [
        //     { label: "social networks",  data: 38.7, color: "#68BC31"},
        //     { label: "search engines",  data: 24.5, color: "#2091CF"},
        //     { label: "ad campaigns",  data: 8.2, color: "#AF4E96"},
        //     { label: "direct traffic",  data: 18.6, color: "#DA5430"},
        //     { label: "other",  data: 10, color: "#FEE074"}
        // ]
        // function drawPieChart(placeholder, data, position) {
        //     $.plot(placeholder, data, {
        //         series: {
        //             pie: {
        //                 show: true,
        //                 tilt:0.8,
        //                 highlight: {
        //                     opacity: 0.25
        //                 },
        //                 stroke: {
        //                     color: '#fff',
        //                     width: 2
        //                 },
        //                 startAngle: 2
        //             }
        //         },
        //         legend: {
        //             show: true,
        //             position: position || "ne",
        //             labelBoxBorderColor: null,
        //             margin:[-30,15]
        //         }
        //         ,
        //         grid: {
        //             hoverable: true,
        //             clickable: true
        //         }
        //     })
        // }
        // drawPieChart(placeholder, data);

        /**
         we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
         so that's not needed actually.
         */
        // placeholder.data('chart', data);
        // placeholder.data('draw', drawPieChart);


        //pie chart tooltip example
        var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
        var previousPoint = null;

        placeholder.on('plothover', function (event, pos, item) {
            if(item) {
                if (previousPoint != item.seriesIndex) {
                    previousPoint = item.seriesIndex;
                    var tip = item.series['label'] + " : " + item.series['percent']+'%';
                    $tooltip.show().children(0).text(tip);
                }
                $tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
            } else {
                $tooltip.hide();
                previousPoint = null;
            }

        });

        /////////////////////////////////////
        $(document).one('ajaxloadstart.page', function(e) {
            $tooltip.remove();
        });


        var d1 = [];
        for (var i = 0; i < Math.PI * 2; i += 0.5) {
            d1.push([i, Math.sin(i)]);
        }

        var d2 = [];
        for (var i = 0; i < Math.PI * 2; i += 0.5) {
            d2.push([i, Math.cos(i)]);
        }

        var d3 = [];
        for (var i = 0; i < Math.PI * 2; i += 0.2) {
            d3.push([i, Math.tan(i)]);
        }


        /*var sales_charts = $('#sales-charts').css({'width':'100%' , 'height':'220px'});
         $.plot("#sales-charts", [
         { label: "Domains", data: d1 },
         { label: "Hosting", data: d2 },
         { label: "Services", data: d3 }
         ], {
         hoverable: true,
         shadowSize: 0,
         series: {
         lines: { show: true },
         points: { show: true }
         },
         xaxis: {
         tickLength: 0
         },
         yaxis: {
         ticks: 10,
         min: -2,
         max: 2,
         tickDecimals: 3
         },
         grid: {
         backgroundColor: { colors: [ "#fff", "#fff" ] },
         borderWidth: 1,
         borderColor:'#555'
         }
         });*/


        $('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
        function tooltip_placement(context, source) {
            var $source = $(source);
            var $parent = $source.closest('.tab-content')
            var off1 = $parent.offset();
            var w1 = $parent.width();

            var off2 = $source.offset();
            //var w2 = $source.width();

            if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
            return 'left';
        }


        $('.dialogs,.comments').ace_scroll({
            size: 300
        });


        //Android's default browser somehow is confused when tapping on label which will lead to dragging the task
        //so disable dragging when clicking on label
        var agent = navigator.userAgent.toLowerCase();
        if("ontouchstart" in document && /applewebkit/.test(agent) && /android/.test(agent))
            $('#tasks').on('touchstart', function(e){
                var li = $(e.target).closest('#tasks li');
                if(li.length == 0)return;
                var label = li.find('label.inline').get(0);
                if(label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation() ;
            });

        $('#tasks').sortable({
                    opacity:0.8,
                    revert:true,
                    forceHelperSize:true,
                    placeholder: 'draggable-placeholder',
                    forcePlaceholderSize:true,
                    tolerance:'pointer',
                    stop: function( event, ui ) {
                        //just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
                        $(ui.item).css('z-index', 'auto');
                    }
                }
        );
        $('#tasks').disableSelection();
        $('#tasks input:checkbox').removeAttr('checked').on('click', function(){
            if(this.checked) $(this).closest('li').addClass('selected');
            else $(this).closest('li').removeClass('selected');
        });


        //show the dropdowns on top or bottom depending on window height and menu position
        $('#task-tab .dropdown-hover').on('mouseenter', function(e) {
            var offset = $(this).offset();

            var $w = $(window)
            if (offset.top > $w.scrollTop() + $w.innerHeight() - 100)
                $(this).addClass('dropup');
            else $(this).removeClass('dropup');
        });

        //And for the first simple table, which doesn't have TableTools or dataTables
        //select/deselect all rows according to table header checkbox
        var active_class = 'active';
        $('#dynamic-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
            var th_checked = this.checked;//checkbox inside "TH" table header

            $(this).closest('table').find('tbody > tr').each(function(){
                var row = this;
                if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
                else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
            });
        });

        //select/deselect a row when the checkbox is checked/unchecked
        $('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
            var $row = $(this).closest('tr');
            if($row.is('.detail-row ')) return;
            if(this.checked) $row.addClass(active_class);
            else $row.removeClass(active_class);
        });


    })
</script>
</body>
</html>