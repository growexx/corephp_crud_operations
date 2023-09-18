function Sidebar() {
    $('#SidebarBlock').html('  <div class="sidebar-user">'+
       '     <ul class="sidebar-menu">'+
       '         <li class="menu-header">Content</li>'+
       '         <li class="active">'+
        '            <a href="index.html"><i class="fa fa-user"></i><span>User List</span></a>'+
        '        </li>'+
       '     </ul>'+
        '     <ul class="sidebar-menu">'+
       '         <li class="menu-header">Logout</li>'+
       '         <li class="active">'+
        '            <a onclick="logout();"><i fa fa-sign-out" aria-hidden="true"></i><span>Logout</span></a>'+
        '        </li>'+
       '     </ul>'+
       '</div>');
    }