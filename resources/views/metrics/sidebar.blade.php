<!-- admin-logo -->
<ul class="list-unstyled menu-parent" id="mainMenu">
	<li class=''>
		<a href="/dashboard" class="waves-effect waves-light">
			<i class="icon  ti-ruler-pencil "></i>
			<span class="text">Portfolio Performance</span>
		</a>
	</li>
	@if($role_user!=Config::get('roles.FINANCIAL') && $role_user!=Config::get('roles.USER') && $role_user!=Config::get('roles.EMPLOYEE'))
	<li class=''>
		<a href="/evaluations" class="waves-effect waves-light">
			<i class="icon  ti-medall-alt"></i>
			<span class="text">Team Evaluations</span>
		</a>
	</li>
	@endif
	@if($role_user==Config::get('roles.ADMIN') || $role_user==Config::get('roles.DASHBOARD_MANAGER') || $role_user==Config::get('roles.DIR_POS') || $role_user==Config::get('roles.AREA_SUPERVISOR') || $role_user==Config::get('roles.SUPERVISOR') || $role_user==Config::get('roles.AREA_MANAGER')) 
	<li class=''>
		<a href="/quality" class="waves-effect waves-light">
			<i class="icon  ti-bookmark-alt "></i>
			<span class="text">Quality Assurance</span>
		</a>
	</li>
	@endif
	
	@if(Auth::user()->hasRole(1)==Config::get('roles.ADMIN') || $role_user==Config::get('roles.DIR_POS'))  
	<li class=''>
		<a href="/reports" class="waves-effect waves-light">
			<i class="icon ti-image"></i>
			<span class="text">Reports Dashboard</span>
		</a>
	</li>
	@endif
	@if(Auth::user()->hasRole(1)==Config::get('roles.ADMIN'))
	<li class="submenu">
		<a class="waves-effect waves-light" href="#menu_users">
			<i class="icon ti-user"></i>
			<span class="text">Users</span>
			<i class="chevron ti-angle-right"></i>
		</a>
		<ul class="list-unstyled" id="menu_users">
			<li><a href="/users">System Users</a></li>
			<li><a href="/employee">Employee</a></li>
		</ul>
	</li>
	@endif

	<!--<li>
		<a href="/metrics">
			<i class="icon  ti-ruler-pencil "></i>
			<span class="text">Portfolio Performance</span>
		</a>
	</li>-->
	@if($role_user==Config::get('roles.ADMIN') || $role_user==Config::get('roles.DASHBOARD_MANAGER') || $role_user==Config::get('roles.DIR_POS') || $role_user==Config::get('roles.AREA_MANAGER') || $role_user==Config::get('roles.USER') || $role_user==Config::get('roles.SUPERVISOR') || $role_user==Config::get('roles.AREA_SUPERVISOR')) 
	<li class=''>
		<a href="/tools" class="waves-effect waves-light">
			<i class="icon  ion-ios-keypad-outline"></i>
			<span class="text">Tools</span>
		</a>
	</li>
	@endif
	@if($role_user==Config::get('roles.ADMIN') || $role_user==Config::get('roles.DASHBOARD_MANAGER') || $role_user==Config::get('roles.DIR_POS'))
		<li class=''>
			<a href="#menu_announcement" class="waves-effect waves-light">
				<i class="icon ti-announcement"></i>
				<span class="text">Announcement</span>
				<i class="chevron ti-angle-right"></i>
			</a>
			<ul class="list-unstyled" id="menu_announcement">
				<li><a href="/announcement">To Emails</a></li>
				<li><a href="/announcement-dashboard">To Dashboard</a></li>
			</ul>
		</li>
		<li class=''>
			<a href="#menu_request_history" class="waves-effect waves-light">
				<i class="icon ti-blackboard "></i>
				<span class="text">Requests History</span>
				<i class="chevron ti-angle-right"></i>
			</a>
			<ul class="list-unstyled" id="menu_announcement">
				<li><a href="/history-job">Job Request</a></li>
				<li><a href="/history-talent-change">Talent Change Request</a></li>
				<li><a href="/history-training">Training Registration</a></li>
				<li><a href="/history-exit-interview">Exit Interview</a></li>
			</ul>
		</li>
		<!--
		<li>
			<a href="/budget">
				<i class="icon  ti-briefcase "></i>
				<span class="text">Budget</span>
			</a>
		</li>-->
	@endif
	@if(Auth::user()->hasRole(1)==Config::get('roles.ADMIN') || Auth::user()->getRole()==Config::get('roles.FINANCIAL')) 
	<li>
		<a href="/financial">
			<i class="icon ti-folder"></i>
			<span class="text">Account Payable</span>
		</a>
	</li>
	@endif
		@if(Auth::user()->getRole()==Config::get('roles.ADMIN') || $role_user==Config::get('roles.DASHBOARD_MANAGER') || Auth::user()->getRole()==Config::get('roles.DIR_POS') || Auth::user()->getRole()==Config::get('roles.AREA_MANAGER'))
		<li>
			<a href="/quotes">
				<i class="icon ti-comment-alt"></i>
				<span class="text">Quotes</span>
			</a>
		</li>
	@endif
	@if(Auth::user()->hasRole(1)==Config::get('roles.ADMIN') || $role_user==Config::get('roles.DIR_POS') || Auth::user()->getRole()==Config::get('roles.AREA_MANAGER'))  
	<li>
		<a href="/map">
			<i class="icon  ti-location-arrow"></i>
			<span class="text">Job Map</span>
		</a>
	</li>
	@endif
	@if (Auth::user()->getRole() == Config::get('roles.DIR_POS'))
		<li>
			<a href="/questions">
				<i class="icon ti-view-list-alt"></i>
				<span class="text">Questions Management</span>
			</a>
		</li>
		<li>
			<a href="/matrix-options">
				<i class="icon ti-layout-grid2"></i>
				<span class="text">Matrix Options</span>
			</a>
		</li>
		<li>
			<a href="/survey">
				<i class="icon ti-bar-chart"></i>
				<span class="text">Survey Management</span>
			</a>
		</li>
		<li>
			<a href="/survey-reports">
				<i class="icon ti-bar-chart"></i>
				<span class="text">Survey Reports</span>
			</a>
		</li>
	@endif
				
	@if(Auth::user()->hasRole(1)==Config::get('roles.ADMIN'))
	<li>
		<a href="/payroll-tools">
			<i class="icon  ti-mobile "></i>
			<span class="text">Payroll</span>
		</a>
	</li>
	@endif
</ul>

@if($role_user==Config::get('roles.AREA_MANAGER')) 
<ul class="list-unstyled menu-parent" id="mainMenuThree">
	<li class="submenu">
		<a class="waves-effect waves-light" id="btn_menu_site" href="/dashboard">
			<i class="fa fa-suitcase"></i>
			 <span class="text"><strong>SITE</strong></span>
			<i class="chevron ti-angle-right"></i>
		</a>
		<ul class="list-unstyled" id="menu_site">
			@forelse ($job_site as $item)
				 <li><a <?=($item->job_number==$job_portfolio_id)?'class="active-item"':'' ?> href="/metrics/detail/{{ $item->job_number }}/0"> {{ $item->job_description }}</a></li>	
			@empty
			     <li class='current'> None  </li>	
			@endforelse
		</ul>
	</li>
	<li class="submenu">
		<a class="waves-effect waves-light" id="btn_menu_portfolio" href="/dashboard">
			<i class="fa fa-building-o"></i>
			 <span class="text"><strong>PORTFOLIO</strong></span>
			<i class="chevron ti-angle-right"></i>
		</a>
		<ul class="list-unstyled" id="menu_portfolio">
			@forelse ($job_portfolio as $item)
				 <li > <a <?=($item->job_number==$job_portfolio_id)?'class="active-item"':''?> href="/metrics/detail/0/{{ $item->job_number }}">{{ $item->job_description }} </a></li>
			@empty
			     <li class='current'> <span class="child"><i class="fa fa-circle-thin"></i> None</span> <a href=""></a>  </li>	
			@endforelse
		</ul>
	</li>
</ul>
@endif

@if($role_user==Config::get('roles.DIR_POS')) 
<ul class="list-unstyled menu-parent" id="mainMenuThree">
	<li class="submenu">
		<a class="waves-effect waves-light" id="btn_menu_site" href="/dashboard">
			<i class="fa fa-suitcase"></i>
			 <span class="text"><strong>MANAGERS</strong></span>
			<i class="chevron ti-angle-right"></i>
		</a>
		<ul class="list-unstyled" id="menu_site">
			@forelse ($manager_list as $manager)
				 <li > <a <?=($manager->manager_id==$manager_id)?'class="active-item"':''?> href="/metrics/manager/{{ $manager->manager_id }}">{{ $manager->first_name }} {{ $manager->last_name }} </a></li>
			@empty
			     <li class='current'> <span class="child"><i class="fa fa-circle-thin"></i> None</span> <a href=""></a>  </li>	
			@endforelse
			<!--Corporate & None-->
			<li > <a <?=(90==$manager_id)?'class="active-item"':''?> href="/metrics/manager/90">Corporate</a></li>	
			<li > <a <?=(91==$manager_id)?'class="active-item"':''?> href="/metrics/manager/91">None</a></li>	
		</ul>
	</li>
</ul>
@endif