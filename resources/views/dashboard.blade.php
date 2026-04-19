<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
</head>
<body>
    <div style="max-width: 800px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
        
        <h2>Welcome, {{ Auth::user()->name }}!</h2>
        
        @if (session('success'))
            <p style="color: green;"><b>{{ session('success') }}</b></p>
        @endif

        <hr>

        @if(Auth::user()->memberships->isEmpty())
            <div style="background-color: #fff3cd; padding: 15px; border-left: 5px solid #ffc107; margin-bottom: 20px;">
                <h3>Step 1: Membership Registration</h3>
                <p>You must complete your membership payment before you can enroll in courses or training sessions.</p>
                
                <a href="{{ route('memberships.create') }}" style="display: inline-block; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
                    Pay Membership (RM20 / RM200)
                </a>
            </div>
        @else
            <div style="background-color: #d4edda; padding: 15px; border-left: 5px solid #28a745; margin-bottom: 20px;">
                <h3 style="color: #155724;">Membership Active</h3>
                <p>Your membership is confirmed. You are ready to train!</p>
            </div>

            <div style="margin-top: 20px;">
                <h3>Step 2: Enroll in Training</h3>
                <p>Select your Gelanggang and Course below.</p>
                
                <a href="#" style="display: inline-block; padding: 10px 15px; background-color: #17a2b8; color: white; text-decoration: none; border-radius: 4px;">
                    Enroll Now
                </a>
            </div>
        @endif

        <hr>

        <form action="{{ route('logout') }}" method="POST" style="margin-top: 20px;">
            @csrf
            <button type="submit" style="padding: 10px 20px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Logout
            </button>
        </form>

    </div>
</body>
</html>