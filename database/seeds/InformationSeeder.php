<?php

use App\Information;
use Illuminate\Database\Seeder;

class InformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->information() as $info) {
            Information::create($info);
        }
    }


    private function information()
    {
        return [
            [
                'key' => config('const.info_keys.about'),
                'info' => '<!DOCTYPE html>
                            <html lang="en">

                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                                <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
                                <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
                                <title>About Us</title>
                                <style>
                                    * {
                                        font-family: "Lato";
                                        text-align: center;
                                    }
                                    
                                    body {
                                        padding: 10px;
                                    }

                                    ul {
                                        list-style-type: none;
                                        margin: 0px;
                                        padding: 0px;
                                        line-height: 1.8em
                                    }
                                    
                                    a {
                                        display: inline;
                                        font-size: 30px;
                                        color: #008080
                                    }
                                    
                                    a>span {
                                        padding-left: 15px;
                                        padding-top: 10px;
                                    }
                                </style>
                            </head>

                            <body>
                                <h1>About Us</h1>
                                <h2>Changing The Wedding Game</h2>
                                <p>I Do is a Website and App platform to discover your dream Proposal and Wedding, and make it a reality. I Do believes that a Wedding is conceptualized at the thought of proposing. </p>

                                <p>Whether you are looking for ideas to propose, or to hire wedding planners, or looking for the top photographers, or just some ideas and inspiration for your Proposal and Wedding, I DO can help you solve your planning woes through its unique features.</p>
                                <p>With an option to create chats, checklist, inbuilt budget calculator, detailed vendor list, inspiration gallery – you won’t need to spend stressed hours planning a Proposal or Wedding anymore. We are here to change the wedding game and have fun!</p>

                                <h2>Contact Us</h2>
                                <ul>
                                    <li>Phone +254 700323810 </li>
                                    <li>Email: info@i-doapp.com</li>
                                </ul><br>
                                <a href="#"><span class="fa fa-facebook"></span></a>
                                <a href="#"><span class="fa fa-twitter"></span></a>
                                <a href="#"><span class="fa fa-instagram"></span></a>
                                <a href="#"><span class="fa fa-youtube"></span></a>
                                <a href="#"><span class="fa fa-pinterest"></span></a>
                            </body>
                            </html>',
            ],
            [
                'key' => config('const.info_keys.terms'),
                'info' => '<!DOCTYPE html>
                            <html lang="en">

                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                                <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
                                <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
                                <title> Terms And Conditions</title>
                                <style>
                                    * {
                                        font-family: "Lato";
                                    }
                                    body {
                                        padding: 10px;
                                    }
                                    
                                    li {
                                        padding-top: 10px;
                                    }
                                    
                                    a {
                                        display: inline;
                                        color: #008080
                                    }
                                    
                                    a>span {
                                        padding-left: 15px;
                                        padding-top: 10px;
                                    }
                                </style>
                            </head>

                            <body>
                                <h1>TERMS AND CONDITIONS FOR ‘I DO’</h1>
                                <h2>PLEASE READ THE TERMS OF USE THOROUGHLY AND CAREFULLY.</h2>
                                <p>
                                    XXXX Private Limited ("I DO" or "we") is a wedding mobile application and website that provides valuable wedding-related information for the modern prospective couple. The services offered by us include the I DO website located at <a href="">i-doapp.com</a>,
                                    and any other feature, content or applications offered from time to time by I DO in connection with the I DO Website whether accessed directly or through our application for mobile devices (collectively, the "I DO Services").
                                </p>
                                <p>
                                    The terms and conditions set forth below ("Terms of Use") and the Privacy Policy (as defined below) constitute a legally-binding agreement between I DO Wedding Services and you. These Terms of Use contain provisions that define your limits, legal rights
                                    and obligations with respect to your use of and participation in (i) the I DO website, including the classified advertisements, forums, various email functions and Internet links, and all content and I DO services available through the domain
                                    and sub-domains of I DO located at
                                    <a href="i-doapp.com">i-doapp.com</a> (collectively referred to herein as the "Website"), and (ii) the online transactions between those users of the Website who are offering services (each, a "Service Professional") and those users of the Website
                                    who are obtaining services (each, a "Service User") through the Website (such services, collectively, the "Services"). The Terms of Use described below incorporate the Privacy Policy and apply to all users of the Website, including users who are
                                    also contributors of video content, information, private and public messages, advertisements, and other materials or Services on the Website.
                                </p>
                                <p>
                                    I DO may modify this Agreement from time to time and each modification will be effective when it is posted on the I DO Website. You agree to be bound to any changes to this Agreement through your continued use of the I DO Services. You will not be notified
                                    of any modifications to this Agreement so it is important that you review this Agreement regularly to ensure you are updated as to any changes.
                                </p>

                                <p>
                                    <b>
                                        WE URGE YOU TO THINK BEFORE YOU UPLOAD, SUBMIT OR EMBED CONTENT. THIS AGREEMENT PERMITS YOU TO UPLOAD TO , SUBMIT TO OR EMBED ON THE I DO SERVICES ONLY PHOTOS OR OTHER CONTENT THAT YOU OWN THE COPYRIGHT TO OR OTHERWISE HAVE THE RIGHT TO PUBLISH. BY UPLOADING, SUBMITTING OR EMBEDDING PHOTOS OR OTHER CONTENT THAT YOU DO NOT OWN THE COPYRIGHT TO OR DO NOT OTHERWISE HAVE THE RIGHT TO PUBLISH, YOU MAY SUBJECT YOURSELF TO LEGAL LIABILITY (SEE E.G., SECTIONS 4, 5 AND 6 BELOW). IT IS YOUR RESPONSIBILITY TO ENSURE YOU HAVE ADEQUATE RIGHTS TO PUBLISH TO THE I DO SERVICES ALL PHOTOS AND OTHER CONTENT YOU POST.
                                    </b>
                                </p>

                                <ol>
                                    <li>
                                        <p><b>Eligibility.</b> Use of the I DO Services is void where prohibited. By registering, you (i) represent and warrant that you have the right, authority, and capacity to enter into and to fully abide by all of the terms and conditions of this
                                            Agreement, and (ii) agree to comply with all applicable domestic and international laws, statutes, ordinances and regulations regarding your use of the I DO Services</p>
                                    </li>
                                    <li>
                                        <p><b>Term.</b> This Agreement shall remain in full force and effect while you use the I DO Services or are a User/ Vendor on the website. I DO may terminate your use of the I DO Website or the I DO Services, in its sole discretion, for any reason
                                            or no reason whatsoever, at any time, without warning or notice to you.</p>
                                    </li>
                                    <li>
                                        <p><b>User Content.</b>
                                            <ul>
                                                <li>I DO does not claim any ownership rights in the text, files, images, photos, video, sounds, musical works, works of authorship, or any other materials (collectively, "User Content") that you upload to, submit to, or embed on the I
                                                    DO Services. You represent and warrant that you own the User Content posted by you on or through the I DO Services or that you otherwise have sufficient right, title and interest in and to such User Content to grant I DO the licenses
                                                    and rights set forth below without violating, infringing or misappropriating the privacy rights, publicity rights, copyrights, contract rights, intellectual property rights or any other rights of any person. You agree to pay for
                                                    all royalties, fees, and any other monies owing any person by reason of any User Content posted by you to or through the I DO Services</li>
                                                <li>After posting, uploading or embedding User Content to the I DO Services, you continue to retain such rights in such User Content as you held prior to posting such User Content on the I DO Services and you continue to have the right
                                                    to use your User Content in any way you choose. However, by displaying or publishing ("posting") any User Content on or through the I DO Services, you hereby grant to I DO a non-exclusive, royalty-free, transferable, sublicensable,
                                                    worldwide license to use, display, reproduce, adapt, modify (e.g., re-format), re-arrange, and distribute your User Content through any media now known or developed in the future. Photographs used on the I DO Services on in any
                                                    I DO publication will include attribution to the photographer and/or copyright holder.</li>
                                                <li>Without this license, I DO would be unable to provide the I DO Services or its publications. For example, the license you grant to I DO is non-exclusive (meaning you are free to license your Content to anyone else in addition to I
                                                    DO), fully-paid and royalty-free (meaning that I DO is not required to pay you for the use of the User Content that you post), sublicensable (so that I DO is able to use its affiliates and subcontractors such as Internet content
                                                    delivery networks to provide the I DO Services), and worldwide (because the Internet and the I DO Services are global in reach).</li>
                                                <li>This license will terminate at the time you remove your User Content from the I DO Services except as to any User Content that I DO has sublicensed prior to your removal of your User Content from the I DO Services, which license shall
                                                    continue in perpetuity.. To remove User Content, please send a request to <a href="mailto:info@i-doapp.com">info@i-doapp.com</a> and include a brief description of the item(s) to be removed along with a URL of the item(s) current
                                                    location on the I DO Website. We will remove the item(s) as quickly as possible.</li>
                                                <li>The I DO Services contain Content owned by I DO ("I DO Content"). I DO Content is protected by copyright, trademark, patent, trade secret and other laws, and I DO owns and retains all rights in the I DO Content and the I DO Services.
                                                    I DO hereby grants you a limited, revocable, non-sublicensable license to view the I DO Content (excluding any software code) solely for your personal use in connection with viewing the I DO Website and using the I DO Services.
                                                    Without limiting the generality of the foregoing, you agree that you shall not copy, modify, translate, publish, broadcast, transmit, license, sublicense, assign, distribute, perform, display, or sell any I DO Content appearing
                                                    on or through the I DO Services.</li>
                                                <li>The I DO Services contain Content of other Users and other I DO licensors ("Third Party Content") and you are permitted to access the Third Party Content solely for your personal use in connection with viewing the I DO Website and
                                                    using the I DO Services. Without limiting the generality of the foregoing, you agree that you shall not copy, modify, translate, publish, broadcast, transmit, license, sublicense, assign, distribute, perform, display, or sell any
                                                    Third Party Content appearing on or through the I DO Services.</li>
                                            </ul>
                                        </p>
                                    </li>
                                    <li>
                                        <p><b>Prohibited Content.</b> I DO reserves the right, in its sole and absolute discretion, to determine whether User Content is appropriate; and to remove any User Content, without notice or liability to you, which it determines to be inappropriate.
                                            Without limiting the generality of the foregoing, the following is a partial list of the types of User Content that I DO deems to be inappropriate:
                                            <ul>
                                                <li>Content that criticizes a business or individual beyond that of merely offering an opinion;</li>
                                                <li>Content that harasses or advocates harassment of another person;</li>
                                                <li>Content that exploits people in a sexual or violent manner;</li>
                                                <li>Content that contains nudity, violence, or offensive subject matter or contains a link to an adult website;</li>
                                                <li>Content that includes racially, ethically, or otherwise objectionable language;</li>
                                                <li>Content that is libelous, defamatory, or otherwise tortious language;</li>
                                                <li>Content that solicits personal information from anyone under 18;</li>
                                                <li>Content that promotes information that you know is false or misleading or promotes illegal activities or conduct that is abusive, threatening, obscene, defamatory or libelous;</li>
                                                <li>Content that promotes an illegal or unauthorized copy of another person’s copyrighted work, such as providing pirated computer programs or links to them, providing information to circumvent manufacture-installed copy-protect devices,
                                                    or providing pirated music or links to pirated music files;</li>
                                                <li>Content that involves the transmission of "junk mail," "chain letters," or unsolicited mass mailing, instant messaging, "spimming," or "spamming";</li>
                                                <li>Content that contains restricted or password only access pages or hidden pages or images (those not linked to or from another accessible page);</li>
                                                <li>Content that furthers or promotes any criminal activity or enterprise or provides instructional information about illegal activities including, but not limited to making or buying illegal weapons, violating someone’s privacy, or providing
                                                    or creating computer viruses;</li>
                                                <li>Content that solicits passwords or personal identifying information for commercial or unlawful purposes from other Users;</li>
                                                <li>Content that involves commercial activities and/or sales without our prior written consent such as contests, sweepstakes, barter, advertising, or pyramid schemes; and</li>
                                            </ul> <br> I DO’s right to remove inappropriate User Content shall not be its sole right with respect to inappropriate User Content and I DO expressly reserves the right to investigate and take appropriate legal action against anyone who, in
                                            I DO’s sole discretion, violates this provision, including without limitation, reporting you to law enforcement authorities.
                                        </p>
                                    </li>
                                    <li>
                                        <p><b>Prohibited Activity.</b>You expressly agree that you are prohibited from engaging in, and will not engage in, the following prohibited activities in connection with your use of the I DO Services:
                                            <ul>
                                                <li>copying, modifying, translating, publishing, broadcasting, transmitting, licensing, sublicensing, assigning, distributing, performing, publicly displaying, or selling any Third Party Content or I DO Content appearing on or through
                                                    the I DO Services;</li>
                                                <li>criminal or tortious activity, including child pornography, fraud, trafficking in obscene material, drug dealing, gambling, harassment, stalking, spamming, spimming, sending of viruses or other harmful files, copyright infringement,
                                                    patent infringement, or theft of trade secrets;</li>
                                                <li>covering or obscuring the banner advertisements on your personal profile page, or any I DO page via HTML/CSS or any other means;</li>
                                                <li>any automated use of the system, such as using scripts to add friends or send comments or messages;</li>
                                                <li>interfering with, disrupting, or creating an undue burden on the I DO Services or the networks or services connected to the I DO Services;</li>
                                                <li>attempting to impersonate another User, person, or representative of I DO;</li>
                                                <li>using the account, username, or password of another User at any time or disclosing your password to any third party or permitting any third party to access your account;</li>
                                                <li>selling or otherwise transferring your profile, without our permission;</li>
                                                <li>using any information obtained from the I DO Services in order to harass, abuse, or harm another person;</li>
                                                <li>displaying an advertisement on your profile, or accepting payment or anything of value from a third person in exchange for your performing any commercial activity on or through the I DO Services on behalf of that person, such as placing
                                                    commercial content on your profile, posting blogs or bulletins with a commercial purpose, or sending private messages with a commercial purpose; or</li>
                                                <li>using the I DO Services in a manner inconsistent with any and all applicable laws and regulations.</li>
                                            </ul>
                                        </p>
                                    </li>
                                    <li>
                                        <p><b>Copyright Policy.</b> You may not post, modify, distribute, or reproduce in any way any copyrighted material, trademarks, or other proprietary information belonging to I DO or others (including without limitation Third Party Content or
                                            I DO Content) without obtaining the prior written consent of the owner of such copyrighted material, trademarks, or other proprietary information. If we become aware that one of our users is a repeat copyright infringer, it is our policy
                                            to take reasonable steps within our power to terminate that user. Without limiting the foregoing, if you believe that your work has been copied and posted on the I DO Services in a way that constitutes copyright infringement, please provide
                                            us with relevant proof and we’ll be happy to take corrective action accordingly</p>
                                    </li>
                                    <li>
                                        <p><b>User Disputes. </b>You are solely responsible for your interactions with other I DO Users. I DO reserves the right, but has no obligation, to monitor disputes between you and other Users and to immediately terminate the privileges of any
                                            User for any reason or for no reason.</p>
                                    </li>
                                    <li>
                                        <p><b>Privacy.</b> Use of the I DO Services is also governed by our Privacy Policy, located at
                                            <a href="i-doapp.com/privacy">i-doapp.com/privacy</a> and incorporated into this Agreement by this reference.</p>
                                    </li>
                                    <li>
                                        <p><b>Promotions and Giveaways.</b>From time to time, I DO will offer sweepstakes, promotions or giveaways on behalf of third parties. Each promotion or giveaway will have its own rules that will disclose what information is gathered, how that
                                            information is used, and who that information shared with. I DO encourages you to review such information prior to engaging with each sweepstakes, promotion or giveaway.</p>
                                    </li>
                                    <li>
                                        <p><b>Disclaimer of Warranties.</b> THE I DO SERVICE IS PROVIDED TO YOU ON AN "AS IS" AND "AS AVAILABLE" BASIS WITHOUT REPRESENTATIONS OR WARRANTIES OF ANY KIND AND I DO EXPRESSLY DISCLAIMS ANY AND ALL IMPLIED OR STATUTORY WARRANTIES TO THE MAXIMUM
                                            EXTENT PERMITTED BY APPLICABLE LAW, INCLUDING WITHOUT LIMITATION IMPLIED WARRANTIES OF TITLE, NON-INFRINGEMENT, MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE. NO ADVICE OR INFORMATION, WHETHER ORAL OR WRITTEN, OBTAINED BY YOU FROM
                                            THE WEBSITE OR I DO SERVICES SHALL CREATE ANY WARRANTY NOT EXPRESSLY STATED IN THE TERMS OF USE. AS WITH THE PURCHASE OF A PRODUCT OR SERVICE THROUGH ANY MEDIUM OR IN ANY ENVIRONMENT, YOU SHOULD USE YOUR BEST JUDGMENT AND EXERCISE CAUTION
                                            WHERE APPROPRIATE.</p>
                                        <p>Without limiting the generality of the foregoing, I DO is not responsible for any incorrect or inaccurate Content posted on the I DO Website or in connection with the I DO Services. User Content created and posted on the I DO Website may contain
                                            links to other websites. I DO is not responsible for the accuracy or opinions contained in User Content or on third party websites linked from User Content. Such websites are in no way investigated, monitored or checked for accuracy or
                                            completeness by I DO. Inclusion of any linked website on the I DO Services does not imply approval or endorsement of the linked website by I DO. When you access these third-party sites, you do so at your own risk. I DO takes no responsibility
                                            for third party advertisements which are posted on this I DO Website or through the I DO Services, nor does it take any responsibility for the goods or services provided by its advertisers. I DO is not responsible for the conduct, whether
                                            online or offline, of any User of the I DO Services. I DO assumes no responsibility for any error, omission, interruption, deletion, defect, delay in operation or transmission, communications line failure, theft or destruction or unauthorized
                                            access to, or alteration of, any User communication or any Content. I DO is not responsible for any problems or technical malfunction of any telephone network or lines, computer online systems, servers or providers, computer equipment,
                                            software, failure of any email or players due to technical problems or traffic congestion on the Internet or on any of the I DO Services or combination thereof, including any injury or damage to Users or to any person’s computer related
                                            to or resulting from participation or downloading materials in connection with the I DO Services. Under no circumstances shall I DO be responsible for any loss or damage, including personal injury or death, resulting from use of the I
                                            DO Services, attendance at a I DO event, from any Content posted on or through the I DO Services, or from the conduct of any Users of the I DO Services, whether online or offline. I DO cannot guarantee and does not promise any specific
                                            results from use of the I DO Services.
                                        </p>
                                    </li>
                                    <li>
                                        <p><b>Limitation of Liability.</b>IN NO EVENT SHALL I DO OR ANY PARENT, SUBSIDIARY, AFFILIATE, DIRECTOR, OFFICER, EMPLOYEE, LICENSOR, DISTRIBUTOR, SUPPLIER, AGENT, RESELLER, OWNER, OR OPERATOR OF I DO BE LIABLE TO YOU OR ANY THIRD PARTY FOR ANY
                                            DIRECT, INDIRECT, CONSEQUENTIAL, EXEMPLARY, INCIDENTAL, SPECIAL OR PUNITIVE DAMAGES, INCLUDING LOST PROFIT DAMAGES ARISING FROM YOUR USE OF THE SERVICES, EVEN IF I DO HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. NOTWITHSTANDING
                                            ANYTHING TO THE CONTRARY CONTAINED HEREIN, I DO’S LIABILITY TO YOU FOR ANY CAUSE WHATSOEVER AND REGARDLESS OF THE FORM OF THE ACTION, WILL AT ALL TIMES BE LIMITED TO THE AMOUNT PAID, IF ANY, PAID BY YOU TO I DO FOR THE I DO SERVICES DURING
                                            THE TERM OF YOUR USE. THE FOREGOING LIMITATION OF LIABILITY SHALL APPLY TO THE FULLEST EXTENT PERMITTED BY LAW IN THE APPLICABLE JURISDICTION. YOU SPECIFICALLY ACKNOWLEDGE THAT I DO SHALL NOT BE LIABLE FOR USER CONTENT OR FOR ANY DEFAMATORY,
                                            OFFENSIVE, OR ILLEGAL CONDUCT OF ANY THIRD PARTY AND THAT THE RISK OF HARM OR DAMAGE FROM THE FOREGOING RESTS ENTIRELY WITH YOU. SOME JURISDICTIONS DO NOT ALLOW LIMITATIONS ON IMPLIED WARRANTIES OR THE EXCLUSION OR LIMITATION OF LIABILITY
                                            FOR INCIDENTAL OR CONSEQUENTIAL DAMAGES. ACCORDINGLY, SOME OF THE LIMITATIONS MAY NOT APPLY TO YOU.</p>
                                    </li>

                                    <li>
                                        <p><b>Special Admonitions for International Use.</b>Recognizing the global nature of the Internet, you agree to comply with all local rules regarding online conduct and acceptable Content. Specifically, you agree to comply with all applicable
                                            laws regarding the transmission of technical data exported from the United States or the country in which you reside.</p>
                                    </li>
                                    <li>
                                        <p><b>Disputes; Choice of Law; Venue.</b>If there is any dispute about or involving the I DO Services, you agree that the dispute shall be governed by the laws of Hyderabad, India. The prevailing party in any action brought in connection with
                                            this Agreement shall be entitled to an award of attorneys’ fees and costs incurred by the prevailing party in connection with such action.</p>
                                    </li>
                                    <li>
                                        <p><b>Indemnity.</b>You agree to indemnify and hold harmless I DO, and any parent, subsidiary, and affiliate, director, officer, employee, licensor, distributor, supplier, agent, reseller, owner and operator, from and against any and all claims,
                                            damages, obligations, losses, liabilities, costs or debt, including but not limited to reasonable attorneys’ fees, made by any third party due to or arising out of your use of the I DO Services in violation of this Agreement and/or arising
                                            from: (i) your use of and access to the I DO Website; (ii) your violation of any term of these Terms of Use; (iii) your violation of any third party right, including without limitation any copyright, property, or privacy right; or (iv)
                                            any claim that your User Content caused damage to a third party. This defense and indemnification obligation will survive these Terms of Use and your use of the I DO Website and/or the I DO Services</p>
                                    </li>
                                    <li>
                                        <p><b>Waiver and Severability of Terms.</b>The failure of I DO to exercise or enforce any right or provision of this Agreement shall not constitute a waiver of such right or provision. If any provision of this Agreement is found by a court of
                                            competent jurisdiction to be invalid, the parties nevertheless agree that the court should endeavor to give effect to the parties’ intentions as reflected in the provision, and the other provisions of this Agreement remain in full force
                                            and effect.</p>
                                    </li>
                                    <li>
                                        <p><b>Statute of Limitations. </b>You agree that regardless of any statute or law to the contrary, any claim or cause of action arising out of or related to use of the Service or this Agreement must be filed within one (1) year after such claim
                                            or cause of action arose or be forever barred.</p>
                                    </li>
                                    <li>
                                        <p><b>Violations.</b>Please report any violations of these Terms of Use to us by emailing us at
                                            <a href="mailto:info@i-doapp.com">info@i-doapp.com</a>.</p>
                                    </li>

                                </ol>

                            </body>

                            </html>',
            ],
            [
                'key' => config('const.info_keys.policy'),
                'info' => '<!DOCTYPE html>
                            <html lang="en">

                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                                <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
                                <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
                                <title> Privacy Policy</title>
                                <style>
                                    * {
                                        font-family: "Lato";
                                    }
                                    
                                    body {
                                        padding: 10px;
                                    }
                                    
                                    li {
                                        padding-top: 10px;
                                    }
                                    
                                    a {
                                        display: inline;
                                        color: #008080
                                    }
                                    
                                    a>span {
                                        padding-left: 15px;
                                        padding-top: 10px;
                                    }
                                </style>
                            </head>

                            <body>
                                <h1>Privacy Policy</h1>
                                <p>I Do has created this Privacy Statement (Policy) in order to demonstrate our firm commitment to help our users better understand what information we collect about them and what may happen to that information.</p>
                                <p>The terms &quot;We, I DO, Us&quot; refer to <a href="i-doapp.com">i-doapp.com</a> and the terms &quot;You, Your&quot; refer to a user of <a href="i-doapp.com">i-doapp.com</a>. </p>
                                <p>In the course of our business of helping our viewers plan their wedding, we collect certain information from you.</p>
                                <p>In the course of registering for and availing various services we provide from time to time through our website: I DO, you may be required to give your Name, address, Email address, phone number.</p>
                                <p>The Personal Information is used for three general purposes: to customize the content you see, to fulfill your requests for certain services, and to contact you about our services. Unless otherwise stated explicitly, this Policy applies to Personal
                                    Information as disclosed on any of the Media.</p>
                                <h2>Security</h2>
                                <p>Personal Information will be kept confidential and we do not disclose the information except that in case you have specifically made an enquiry. Further, the vendors / advertisers who are listed with us, may call you, based on the query or enquiry
                                    that you make with us, enquiring about any Product / Service they might offer.</p>
                                <p>We will share Personal Information only under one or more of the following circumstances: - If we have your consent or deemed consent to do so - If we are compelled by law (including court orders) to do so.</p>
                                <p>In furtherance of the confidentiality with which we treat Personal Information we have put in place appropriate physical, electronic, and managerial procedures to safeguard and secure the information we collect online.</p>
                                <p>We give you the ability to edit your account information and preferences at any time, including whether you want us to contact you regarding any services. To protect your privacy and security, we will also take reasonable steps to verify your identity
                                    before granting access or making corrections.
                                </p>
                                <p>We treat data as an asset that must be protected against loss and unauthorized access. We employ many different security techniques to protect such data from unauthorized access by members inside and outside the company. However, &quot;perfect security&quot;
                                    does not exist on the Internet, or anywhere else in the world! You therefore agree that any security breaches beyond the control of our standard security procedures are at your sole risk and discretion.</p>
                                <h2>Links to other Websites</h2>
                                <p>We have affiliate links to many other online resources. We are not responsible for the practices employed by these affiliates or their websites linked to or from <a href="i-doapp.com">i-doapp.com</a> nor the information or content contained on these
                                    third party websites. You should carefully review their privacy statements and other conditions of use and you agree you provide information or engage in transactions with these affiliates at your own risk.</p>
                                <h2>Control Of Your Password</h2>
                                <p>You are responsible for all actions taken with your login information and password, including fees. Therefore we do not recommend that you disclose your account password or login information to any third parties. If you lose control of your password,
                                    you may lose substantial control over your personally identifiable information and may be subject to legally binding actions taken on your behalf. Therefore, if your password has been compromised for any reason, you should immediately change your
                                    password.
                                </p>
                                <h2>Content On The Site</h2>
                                <p><a href="i-doapp.com">i-doapp.com</a> features some of the latest trends in African weddings around the world, and tries to give its users exposure to quality hand-picked content. In our endeavor, we feature pictures and stories from various real
                                    weddings and vendors. Vendors are expected to take utmost care to take permission / hold copyright of images given to us. We also feature weddings and articles where users have given us permission to use the same.</p>
                                <p>However, in the unlikely event of anyone having any objection to content put up on our site, they are free to contact us immediately and we’ll be happy to consider their request and take necessary action.</p>
                                <h2>Updates on Privacy Policy</h2>
                                <p>We reserve the right to revise these Privacy Policies of <a href="i-doapp.com">i-doapp.com</a> from time to time by updating this posting. Such revised policies will take effect as of the date of posting.</p>
                                <p></p>
                                <p></p>
                                <p></p>
                                <p></p>
                                <p></p>
                                <h2>Contact Us</h2>
                                <p>If you have any further queries regarding the privacy policy, feel free to contact us at <a href="mailto:info@i-doapp.com">info@i-doapp.com</a>.
                                </p>


                            </body>

                            </html>',
            ],
        ];
    }
}
