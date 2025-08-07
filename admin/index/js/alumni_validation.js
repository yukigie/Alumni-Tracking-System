
function validateFirstPage() {
  let isValid = true;

  // Clear previous errors
  document.querySelectorAll('.errormsg label').forEach(label => label.textContent = '');
  document.querySelectorAll('#first-page input').forEach(element => element.style.border = '');

  // Validate Alumni Tracking Number
  const trackingNumber = document.getElementById('tracking_number').value.trim();
  if (trackingNumber === "") {
    document.getElementById('tracking_number_error').textContent = 'Alumni Tracking Number is required';
    document.getElementById('tracking_number_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('tracking_number').style.border = '1px solid red';
    isValid = false;
  }

  // Validate Email
  const email = document.getElementById('email').value.trim();
  const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  const allowedDomains = ["gmail.com", "yahoo.com", "outlook.com"];

  if (email === "") {
    document.getElementById('email_error').textContent = 'Email Address is required';
    document.getElementById('email_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('email').style.border = '1px solid red';
    isValid = false;
  } else if (!email.match(emailPattern)) {
    document.getElementById('email_error').textContent = 'Enter a valid email address';
    document.getElementById('email_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('email').style.border = '1px solid red';
    isValid = false;
  } else {
    const emailDomain = email.split('@')[1];
    if (!allowedDomains.includes(emailDomain)) {
      document.getElementById('email_error').textContent = 'Email domain must be one of the following: gmail.com, yahoo.com, outlook.com';
      document.getElementById('email_error').style.margin = '-18px 0px 0px 20px';
      document.getElementById('email').style.border = '1px solid red';
      isValid = false;
    }
  }

  // Validate Password
  const password = document.getElementById('password').value.trim();
  if (!validatePassword(password)) {
    document.getElementById('password_error').textContent = 'Password must be at least 8 characters long, contain at least one number, one uppercase letter, and one special character.';
    document.getElementById('password_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('password').style.border = '1px solid red';
    isValid = false;
  }

  // Validate Confirm Password
  const confirmPassword = document.getElementById('cpassword').value.trim();
  if (confirmPassword === "") {
    document.getElementById('cpassword_error').textContent = 'Confirm Password is required';
    document.getElementById('cpassword_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('cpassword').style.border = '1px solid red';
    isValid = false;
  } else if (confirmPassword !== password) {
    document.getElementById('cpassword_error').textContent = 'Passwords do not match';
    document.getElementById('cpassword_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('cpassword').style.border = '1px solid red';
    isValid = false;
  }

  return isValid;
}

// Validate password format
function validatePassword(password) {
  const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
  return passwordPattern.test(password);
}

// Validate second page fields
function validateSecondPage() {
  let isValid = true;

  // Clear previous errors
  document.querySelectorAll('.errormsg label').forEach(label => label.textContent = '');
  document.querySelectorAll('#second-page input, #second-page select').forEach(element => element.style.border = '');

  // Validate First Name
  const name = document.getElementById('name').value.trim();
  if (name === "") {
    document.getElementById('name_error').textContent = 'This Field is required';
    document.getElementById('name_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('name').style.border = '1px solid red';
    isValid = false;
  }

  // Validate Middle Name
  const mname = document.getElementById('mname').value.trim();
  if (mname === "") {
    document.getElementById('mname_error').textContent = 'This Field is required';
    document.getElementById('mname_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('mname').style.border = '1px solid red';
    isValid = false;
  }

  // Validate Last Name
  const lname = document.getElementById('lname').value.trim();
  if (lname === "") {
    document.getElementById('lname_error').textContent = 'This Field is required';
    document.getElementById('lname_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('lname').style.border = '1px solid red';
    isValid = false;
  }

  // Validate Birthday
  const birthday = document.getElementById('birthday').value.trim();
  if (birthday === "") {
    document.getElementById('birthday_error').textContent = 'This Field is required';
    document.getElementById('birthday_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('birthday').style.border = '1px solid red';
    isValid = false;
  }

  // Validate Batchyear
  const batch_year = document.getElementById('batch_year').value.trim();
  if (batch_year === "") {
    document.getElementById('batch_year_error').textContent = 'This Field is required';
    document.getElementById('batch_year_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('batch_year').style.border = '1px solid red';
    isValid = false;
  }

  // Validate Gender
  const gender = document.getElementById('gender').value.trim();
  if (gender === "") {
    document.getElementById('gender_error').textContent = 'This Field is required';
    document.getElementById('gender_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('gender').style.border = '1px solid red';
    isValid = false;
  }

  // Validate About
  const about = document.getElementById('about').value.trim();
  if (about === "") {
    document.getElementById('about_error').textContent = 'This Field is required';
    document.getElementById('about_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('about').style.border = '1px solid red';
    isValid = false;
  }

  return isValid;
}

// Reset error message and input border on typing
document.querySelectorAll('input').forEach(input => {
  input.addEventListener('input', function() {
    this.style.border = ''; // Reset the border to default
    const errorLabelId = `${this.name}_error`;
    const errorLabel = document.getElementById(errorLabelId);
    if (errorLabel) {
      errorLabel.textContent = ''; // Clear the error message
      errorLabel.style.margin = '0px 0px 0px 0px';;
    }
  });
});

// Disable copy-paste for the confirm password field
document.getElementById('cpassword').addEventListener('paste', function(event) {
  event.preventDefault();
  alert('Copy-paste is disabled for security reasons.');
});
