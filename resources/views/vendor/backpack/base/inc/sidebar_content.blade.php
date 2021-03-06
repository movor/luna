<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->

<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

<li>&nbsp;</li>

{{-- App Specific Links --}}

<li><a href="{{ backpack_url('article') }}"><i class="fa fa-book"></i> <span>Articles</span></a></li>
<li><a href="{{ backpack_url('tag') }}"><i class="fa fa-tag"></i> <span>Tags</span></a></li>
<li><a href="{{ backpack_url('user') }}"><i class="fa fa-users"></i> <span>Users</span></a></li>
<li><a href="{{ backpack_url('newsletter') }}"><i class="fa fa-newspaper-o"></i> <span>Newsletter</span></a></li>

<li>&nbsp;</li>

{{-- /App Specific Links --}}

{{-- Other --}}

<li><a href="{{ backpack_url('elfinder') }}"><i class="fa fa-image"></i> <span>File manager</span></a></li>
<li><a href="{{ backpack_url('redirect-rule') }}"><i class="fa fa-map-signs"></i> <span>Redirect Rules</span></a></li>

{{-- /Other --}}