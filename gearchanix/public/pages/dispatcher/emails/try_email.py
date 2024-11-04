import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

#server connection
server = smtplib.SMTP('smtp.gmail.com', 587)
server.starttls()

#login with email and pass
server.login("gearchanixmotorpool@gmail.com", "vcfi mhvv qulf qtmw")

#message: break down: header
message = MIMEMultipart()
message['From'] = "gearchanixmotorpool@gmail.com"
message['To'] ="darryljhan76@gmail.com"
message['Subject'] = "Test Email"

#body email
body = "This is a trial email"
message.attach(MIMEText(body, 'plain'))

#send email
server.sendmail(message['From'], message['To'], message.as_string())

server.quit()

