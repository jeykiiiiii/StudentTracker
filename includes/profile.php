<?php include("../functions/fetch_user.php"); ?>

<div class="profile-holder">
    <div class="container">
        <div class="card">

        <!-- PROFILE FORM -->
        <form id="profileForm" method="POST" action="../functions/upload_image.php" enctype="multipart/form-data">
            <div style="text-align: center; margin-bottom: 10px;">
                <?php if (isset($user['image']) && !empty($user['image'])): ?>
                    <img id="avatar" src="data:image/jpeg;base64,<?php echo base64_encode($user['image']); ?>" 
                        alt="Profile Picture" style="max-width: 200px; border-radius: 50%;" />
                <?php else: ?>
                    <img id="avatar" src="../assets/images/Image.jpg" alt="Profile Picture" 
                        style="max-width: 200px; border-radius: 50%;" />
                <?php endif; ?>
                <br><br>
                <!-- Initially hidden file input -->
                <input type="file" name="image" id="imageUpload" accept="image/*" onchange="previewImage()" style="display: none;">
                <br><br>
                <input type="text" name="fullName" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" readonly>

                <!-- Profile Buttons -->
                <div class="btn-container">
                    <button type="button" class="btn-edit-profile" onclick="toggleEditProfile(true)">EDIT</button>
                    <button type="submit" class="btn-save-profile" style="display: none;">SAVE</button>
                </div>
            </div>
        </form>

            <h2>
                Hello,
                <?php
                    $name = $user['name'] ?? '';
                    $firstName = explode(' ', $name)[0];
                    echo htmlspecialchars($firstName);
                ?>!
            </h2>
            <p>
                user_id:
                <br>   
                <input type="text" id="userId" placeholder="Enter ID" value="<?php echo htmlspecialchars($user['id_number'] ?? ''); ?>" readonly>
            </p>

        </div>

        <div class="info-card">
            <form id="infoForm" method="POST" action="../functions/update_profile.php">
                <h3>User Information</h3>
                <label>Full Name: <input type="text" name="fullName" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" readonly></label><br>
                <label>Year Level: <input type="text" name="year_level" value="<?php echo htmlspecialchars($user['year_level'] ?? ''); ?>" readonly></label>
                <label>Course: <input type="text" name="course" value="<?php echo htmlspecialchars($user['course'] ?? ''); ?>" readonly></label>
                <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly></label><br>
                <label>Age: <input type="text" name="age" value="<?php echo htmlspecialchars($user['age'] ?? ''); ?>" readonly></label><br>
                <label>Mobile: <input type="text" name="mobile" value="<?php echo htmlspecialchars($user['contact_number'] ?? ''); ?>" readonly></label><br>
                <label>Address: <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" readonly></label><br>

                <div class="btn-container">
                    <button type="button" class="btn-edit-info" onclick="toggleEditInfo(true)">EDIT</button>
                    <button type="submit" class="btn-save-info" style="display: none;">SAVE</button>
                </div>
            </form>
        </div>

        <div class="project-card">
            <h3>Subjects</h3>
            <br>
            <ul id="subject-list">
                <?php if (!empty($subjects)): ?>
                    <?php foreach ($subjects as $subject): ?>
                        <li><input type="text" value="<?php echo htmlspecialchars($subject); ?>" readonly></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No subjects enrolled</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<script>
    function toggleEditProfile(editing) {
        const profileInputs = document.querySelectorAll('#profileForm input');
        const saveBtn = document.querySelector('.btn-save-profile');
        const editBtn = document.querySelector('.btn-edit-profile');
        const imageUpload = document.getElementById('imageUpload');

        profileInputs.forEach(input => {
            if (input.type !== 'file') {
                input.readOnly = !editing;
            }
        }); 
        saveBtn.style.display = editing ? "inline-block" : "none";
        editBtn.style.display = editing ? "none" : "inline-block";
        imageUpload.style.display = editing ? "inline-block" : "none"; 
    }

    function toggleEditInfo(editing) {
        const infoInputs = document.querySelectorAll('#infoForm input');
        const saveBtn = document.querySelector('.btn-save-info');
        const editBtn = document.querySelector('.btn-edit-info');

        infoInputs.forEach(input => input.readOnly = !editing); 
        saveBtn.style.display = editing ? "inline-block" : "none";
        editBtn.style.display = editing ? "none" : "inline-block";
    }

    function previewImage() {
        const fileInput = document.getElementById('imageUpload');
        const avatar = document.getElementById('avatar');

        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatar.src = e.target.result;
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    }

    // Check for success message in URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('updated') === '1') {
            alert('Profile updated successfully!');
            // Remove the parameter from URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        if (urlParams.get('error') === '1') {
            alert('Error updating profile. Please try again.');
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
</script>