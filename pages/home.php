<div class="row">
    <div class="col-md-12">
        <h1 class="text-center">VaultMC</h1>
    </div>
</div>
<br>
<div class="row" align="center">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <div class="row" style="background-color:#303030; border-radius:10px; padding:10px;">
            <div class="col-md-6">
                <h3>Player & Clan Information</h3>
                <form role="form" action="?" method="get">
                <input type='hidden' name='view' value='search'/>
                    <div class="form-group">
                        <label for="playername">Search for a player or clan below</label>
                        <input type="text" class="form-control" name="query" placeholder="Enter your query here.">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            <div class="col-md-6">
                <h3>About this site</h3>
                <p>This custom interface helps you to view information about players on VaultMC.</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
    </div>
</div>
<br>
<br>
<?php include("blog/blog.php") ?>
