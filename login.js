function login(event){
    event.preventDefault();
    const userID = document.getElementById("userID").value.trim();
    const password = document.getElementById("password").value;

    if(password == "patient"){
        window.location.href = "patient_dashboard.html";
    }
    else if(password == "admin"){
        window.location.href = "admin_dashboard.html";
    }
    else{
        alert("Invalid User ID or Password");
    }
}