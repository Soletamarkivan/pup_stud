//Questions

1. May mga times ba na hindi lang isang araw pwede gamitin or irereserve ang isang sasakyan?
2. Wala talang name yung mga client, merong schedule info and contact info nila pero wala silang name, dapat may name rito kasi walang signature
- lagyan na ng name (first name, last name or just name)
3. Pwede kaya drop down ang gas consumption? pero kasi connected siya sa vehicle trip ticket. Or di kaya, pwede na siya isama sa trip ticket tapos sa export na lang pwedeng pumili ng i-export?
- isama na lang ang gas consumption sa trip ticket, tapos sa data analysis na lang siya kukunin

"C:\XAMP\htdocs\Gearchanix\GEARCHANIX-main\gearchanix\public\pages\supervisor"
<td><button class="btn btn-primary" type="button" style="margin-left: 3px;margin-right: 3px;">Edit</button><button class="btn btn-danger" type="button" style="margin-left: 3px;margin-right: 3px;">Delete</button></td>

- yung vehicle trip ticket sa supervisor, kunin na lang yung gui sa dispatcher
<tr>
                                                    <td>&lt;name&gt;</td>
                                                    <td>headmech@gmail.com</td>
                                                    <td>09123456789</td>
                                                    <td>09123456789</td>
                                                    <td><button class="btn btn-primary" type="button" style="background: var(--bs-warning);color: var(--bs-black);">Show</button></td>
                                                    <td>09123456789</td>
                                                    <td><button class="btn btn-primary" type="button" style="margin-left: 3px;margin-right: 3px;">Edit</button><button class="btn btn-danger" type="button" style="margin-left: 3px;margin-right: 3px;">Delete</button>
                                                        <ul class="nav nav-tabs"></ul>
                                                    </td>
                                          </tr>

1. Trip Ticket Odometer sa Supervisor and Dispatcher - sila Disptacher na lang ang maglalagay kasi kapag history na 'di na pwede mabago, e what if pinaandar like test drive yung sasakyan? magugulo sa db kasi kung ano yung nadagdag na odometer reading, siya yung mababago, which is not feasible and mahirap din kunin sa database since baka iba yung date. 
