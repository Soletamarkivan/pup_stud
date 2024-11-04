import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText

def send_roles_email(user_email, user_name, role):
    msg = MIMEMultipart()
    
    #message header
    msg['From'] = "gearchanixmotorpool@gmail.com"
    msg['To'] = user_email
    msg['Subject'] = "Role Confirmation"
    
    #email message body
    body_html = f"""
    <html>
    <body>
        <h2 style="color: #4CAF50;">Hello {user_name}, </h2>
        <p>Your role as a <strong>{role}</strong> has been confirmed. You can now login in to the Gearchanix System</p>
        <p>Click the button below to log in: </p>
        <a href="gearchanix.com"></a>
        <p>Best Regards,<br>PUP Motor Pool Admins</p>
    <body>
    <html>
    """
    
    #attach the hmtl content to the email
    msg.attach(MIMEText(body_html, 'html'))
    
    #email sending setup 
    try:
        server = smtplib.SMTP('smtp.gmail.com', 587)
        server.starttls()
        server.login('gearchanixmotorpool@gmail.com', 'vcfi mhvv qulf qtmw')
        server.sendmail(msg['From'], msg['To'], msg.as_string())
        server.quit()
        print(f"Role Confirmation has been sent successfully!")
    except Exception as e: 
        print(f"Failed to send role confirmation email to {user_email}: {e}")

#function to send emails 
def send_multiple_emails(users):
    for user in users:
        user_email = user['email']
        user_name = user['name']
        role = user['role']
        send_roles_email(user_email, user_name, role)

# Example usage with a list of users
users_list = [
    {"email": "darryljhan76@gmail.com", "name": "Darryl Jhan Oro", "role": "Dispatcher"},
    {"email": "umsworld16@gmail.com", "name": "Quennie Ann Soberano", "role": "Mechanic"},
    {"email": "gearchanix@gmail.com", "name": "Gearchanix", "role": "Admin Clerk"}
]

# Sending emails to all users
send_multiple_emails(users_list)

