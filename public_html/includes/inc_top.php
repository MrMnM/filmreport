<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="home.php">Filmstunden <? echo $GLOBALS['version']; ?></a>
  </div><!-- /.navbar-header -->
  <ul class="nav navbar-top-links navbar-right">

<?
  if ($u_type=="producer") {
      $type = "";
  }else{
      $type = "checked";
  }
  $disabled = "";

  if (basename($_SERVER['PHP_SELF'])=="home.php") {
  ?>
  <input id="switchType" type="checkbox" data-toggle="toggle" data-onstyle="default"  data-on="Freelancer" data-off="Produzent"  <?echo $type; echo $disabled?>>
<?}?>

    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-envelope fa-fw"></i> <i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-messages">
        <li>
          <a href="#">
            <div>
              <strong>TEST</strong>
              <span class="pull-right text-muted">
                <em>Yesterday</em>
              </span>
            </div>
            <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
          </a>
        </li>
        <li class="divider"></li>
        <li>
          <a class="text-center" href="#">
            <strong>Weitere</strong>
            <i class="fa fa-angle-right"></i>
          </a>
        </li>
      </ul><!-- /.dropdown-messages -->
    </li><!-- /.dropdown -->
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-user fa-fw"></i>&nbsp; <?echo htmlspecialchars($u_name, ENT_QUOTES);?>&nbsp;&nbsp; <i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-user">
        <li class="divider"></li>
        <li><a href="user.php"><i class="fa fa-user fa-fw"></i> Pers&ouml;nliche Informationen</a></li>
        <li class="divider"></li>
        <li><a href="login.php?action=logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
      </ul><!-- /.dropdown-user -->
    </li><!-- /.dropdown -->
  </ul> <!-- /.navbar-top-links -->
  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
      <ul class="nav" id="side-menu">
        <li class="">
          <a href="home.php"><i class="fa fa-home fa-fw"></i> Dashboard</a>
        </li>
<!-- FREELANCER -->
        <li class="freelance" style="display:none;" >
          <a href="#"><i  class="fa fa-table fa-fw"></i> Projects<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level collapse in" aria-expanded="true">
            <li>
              <a href="project_overview.php?view=active"><i  class="fa fa-star fa-fw"></i> Aktiv</a>
            </li>
            <li>
              <a href="project_overview.php?view=archive"><i  class="fa fa-archive fa-fw"></i> Beendet</a>
            </li>
          </ul> <!-- /.nav-second-level -->
        </li><!--freelance-->
        <li  class="freelance" style="display:none;" >
          <a href="timer.php"><i class="fa fa-clock-o fa-fw"></i> Timer</a>
        </li>
        <li  class="freelance" style="display:none;" >
          <a href="enquiry.php"><i class="fa fa-calendar-plus-o fa-fw"></i> Anfragen</a>
        </li>
<!-- PRODUCER -->
        <li class="producer" style="display:none;" >
          <a href="p_overview.php"><i  class="fa fa-table fa-fw"></i> Projects</a>
        </li>
      </ul>
    </div><!-- /.sidebar-collapse -->
  </div><!-- /.navbar-static-side -->
</nav>
