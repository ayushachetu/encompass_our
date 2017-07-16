<?php 
	 $roles = array(
            '1' =>  "Administrator",
            '2' =>  "Financial",
            '3' =>  "User",
            '4' =>  "Dir Pos",
            '5' =>  "Area Supervisor",
            '6' =>  "Area Manager",
            '7' =>  "Dashboard Manager",
            '8' =>  "Supervisor",
            '9' =>  "Employee",
        );
?>
<div class="top-bar">
	<nav class="navbar navbar-default top-bar">
		<div class="menu-bar-mobile" id="open-left"><i class="ti-menu"></i>
		</div>
		<ul class="nav navbar-nav navbar-right top-elements">
			<li class="piluku-dropdown">
				<a class="rol-tab">{{$roles[Auth::user()->getRole()]}}</a>
			</li>
			<li class="piluku-dropdown dropdown">
				
				<a href="#" class="dropdown-toggle avatar_width" data-toggle="dropdown" role="button" aria-expanded="false"><span class="avatar-holder"><img src="/assets/images/avatar/one.png" alt=""></span><span class="avatar_info">{{Auth::user()->first_name}}</span><span class="drop-icon"></span></a>
				<ul class="dropdown-menu dropdown-piluku-menu  animated fadeInUp wow avatar_drop neat_drop dropdown-right" data-wow-duration="1500ms" role="menu">
					<li>
						<a href="/user/profile"> <i class="ion-android-settings"></i>Settings</a>
					</li>
					<li>
						<a href="/admin/logout" class="logout_button"><i class="ion-power"></i>Logout</a>
					</li>   
				</ul>
			</li>
		</ul>
	</nav>
</div>
<!-- /top-bar -->

	