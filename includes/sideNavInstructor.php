<div id="sideNav" class="side-nav">
    <button class="close-btn" onclick="toggleNav()">×</button>
    <a href="instructordashboard.php">Home</a>
    <a href="profile.php">Profile</a>
    <a href="classlist.php">Class List</a>
    <a href="addclass.php">Add Class</a>
    <a href="../public/logout.php">Logout</a>
</div>

<script>
    function toggleNav() {
        const sideNav = document.getElementById('sideNav');
        sideNav.style.width = sideNav.style.width === '250px' ? '0' : '250px';
    }
</script>