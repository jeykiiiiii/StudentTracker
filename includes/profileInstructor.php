<?php include("../functions/fetch_user.php"); ?>

<div class="profile-holder">
    <div class="container">
        <div class="card">

        <!-- PROFILE FORM -->
        <form id="profileForm" method="POST" action="../functions/upload_image_instructor.php" enctype="multipart/form-data">
            <div style="text-align: center; margin-bottom: 10px;">
                <?php if ($user['image']): ?>
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
                <input type="text" name="fullName" value="<?php echo $user['name']; ?>" readonly>

                <!-- Profile Buttons -->
                <div class="btn-container">
                    <button type="button" class="btn-edit-profile" onclick="toggleEditProfile(true)">EDIT</button>
                    <button type="submit" class="btn-save-profile" style="display: none;" onclick="saveProfileData()">SAVE</button>
                </div>
            </div>
        </form>

            <h2>
                Hello,
                <?php
                    $name = $user['name'];
                    $firstName = explode(' ', $name)[0];
                    echo $firstName;
                ?>!
            </h2>
            <p>
                user_id:
                <br>   
                <input type="text" id="userId" placeholder="Enter ID" value="<?php echo $user['id_number']; ?>" readonly>
            </p>

        </div>

        <div class="info-card">
            <form id="infoForm" method="POST" action="../functions/fetch_user.php">
                <h3>User Information</h3>
                <label>Full Name: <input type="text" name="fullName" value="<?php echo $user['name']; ?>" readonly></label><br>
                <label>Department: <input type="text" name="course" value="<?php echo $user['department']; ?>" readonly></label>
                <label>Email: <input type="email" name="email" value="<?php echo $user['email']; ?>" readonly></label><br>
                <label>Age: <input type="text" name="age" value="<?php echo $user['age']; ?>" readonly></label><br>
                <label>Mobile: <input type="text" name="mobile" value="<?php echo $user['contact_number']; ?>" readonly></label><br>
                <label>Address: <input type="text" name="address" value="<?php echo $user['address']; ?>" readonly></label><br>

                <div class="btn-container">
                    <button type="button" class="btn-edit-info" onclick="toggleEditInfo(true)">EDIT</button>
                    <button type="submit" class="btn-save-info" style="display: none;" onclick="saveInfoData()">SAVE</button>
                </div>
            </form>
        </div>

        <div class="project-card">
            <!-- Instructor's Managed Subjects -->
            <?php if ($role === 'instructor'): ?>
                <h3>Subjects Managed</h3>
                <ul id="managed-subject-list">
                    <?php foreach ($managedSubjects as $managedSubject): ?>
                        <li><input type="text" value="<?php echo htmlspecialchars($managedSubject); ?>" readonly></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function toggleEditProfile(editing) {
        const profileInputs = document.querySelectorAll('#profileForm input');
        const saveBtn = document.querySelector('.btn-save-profile');
        const editBtn = document.querySelector('.btn-edit-profile');
        const imageUpload = document.getElementById('imageUpload');

        profileInputs.forEach(input => input.readOnly = !editing); 
        saveBtn.style.display = editing ? "inline-block" : "none";
        editBtn.style.display = editing ? "none" : "inline-block";
        imageUpload.style.display = editing ? "inline-block" : "none"; 
    }

    function saveProfileData() {
        alert("Profile updated successfully!");
        toggleEditProfile(false);
    }

    function toggleEditInfo(editing) {
        const infoInputs = document.querySelectorAll('#infoForm input');
        const saveBtn = document.querySelector('.btn-save-info');
        const editBtn = document.querySelector('.btn-edit-info');

        infoInputs.forEach(input => input.readOnly = !editing); 
        saveBtn.style.display = editing ? "inline-block" : "none";
        editBtn.style.display = editing ? "none" : "inline-block";
    }

    function saveInfoData() {
        alert("User information updated successfully!");
        toggleEditInfo(false);
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
</script>
