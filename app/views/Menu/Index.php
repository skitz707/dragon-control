<?php
function RenderCharacterDDL()
{
    //header
    echo '<ul class = "navbar-nav">';
    echo '<li class="nav-item dropdown">';
    echo '<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>';
    echo '<div class="dropdown-menu">';
    echo '<a class="dropdown-item" href="#">Action</a>';
    echo '<a class="dropdown-item" href="#">Another action</a>';
    echo '<a class="dropdown-item" href="#">Something else here</a>';
    echo '<div class="dropdown-divider"></div>';
    echo '<a class="dropdown-item" href="#">Separated link</a>';
    echo '</div>';
    //end
    echo '</li>';
    echo '</ul>';
}
?>
		
<nav class="navbar navbar-expand-sm navbar-light bg-light">
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav-content" aria-controls="nav-content" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>

<!-- Brand -->
<a class="navbar-brand" href="#">Dragon Control</a>

<!-- Links -->
<div class="collapse navbar-collapse" id="nav-content">   
<ul class=" nav navbar-nav">
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle font-weight-bold" data-toggle="dropdown" id="Preview" href="#" role="button" aria-haspopup="true" aria-expanded="false">
Characters
</a>
<div class="dropdown-menu" aria-labelledby="Preview">
<a class="dropdown-item" href="#">+ New</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="#">Jugs McBuldge</a>
<a class="dropdown-item" href="#">Frak Reynolds</a>
</div>
</li>
</ul>
</div>

<ul class="nav navbar-nav ml-auto">
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle font-weight-bold" data-toggle="dropdown" id="Preview" href="#" role="button" aria-haspopup="true" aria-expanded="false">
Handle
</a>
<div class="dropdown-menu dropdown-menu-right text-muted" aria-labelledby="Preview">
<a class="dropdown-item" href="#">Info</a>
<a class="dropdown-item" href="login/logoff">Sign Off</a>
</div>
</li>
</ul>
</nav>