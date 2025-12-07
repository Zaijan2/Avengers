<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welcome</title>

<style>
    body{
        margin:0;
        padding:0;
        background:#FFDB58; /* Yellow background */
        display:flex;
        justify-content:center;
        align-items:center;
        height:100vh;
        font-family: "Poppins", sans-serif;
    }

    .container{
        text-align:center;
        opacity:0;
        animation: fadeIn 2s forwards;
    }

    @keyframes fadeIn {
        from { opacity:0; transform:translateY(20px); }
        to   { opacity:1; transform:translateY(0); }
    }

    .logo{
        width:220px;
        height:220px;
        border-radius:40%; /* Round logo */
        object-fit:cover;
        margin-bottom:20px;
        border:5px solid white;
    }

    .tagline{
        font-size:22px;
        font-weight:600;
        color:#2E89F0;
        margin-bottom:25px;
    }

    .enter-btn{
        background:#2E89F0;
        color:white;
        padding:12px 32px;
        font-size:18px;
        border:none;
        border-radius:25px;
        cursor:pointer;
        transition:0.3s;
        text-decoration:none; /* ensures no underline */
        display:inline-block;
    }

    .enter-btn:hover{
        background:#1c6ed6;
        transform:scale(1.05);
    }
</style>

</head>
<body>

<div class="container">

    <!-- Logo -->
    <img src="logo.png" class="logo">

    <!-- Tagline -->
    <div class="tagline">QuickClean: Clean Spaces, Happy Faces.</div>

    <!-- ENTER BUTTON USING HREF -->
    <a href="home.php" class="enter-btn">Get started!</a>

</div>

</body>
</html>
