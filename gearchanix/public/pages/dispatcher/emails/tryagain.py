import smtplib
# creates SMTP session
s = smtplib.SMTP('smtp.gmail.com', 587)
# start TLS for security
s.starttls()
# Authentication
s.login("gearchanix@gmail.com", "G3arChan1x7")
# message to be sent
message = "This is a trial message"
# sending the mail
s.sendmail("gearchanix@gmail.com", "darryljhan76@gmail.com", message)
# terminating the session
s.quit()
