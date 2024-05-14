@extends('layouts.template')

@section('content')
    <style>
        a {
            color: black;
            /* color: whitesmoke; */
            text-decoration: none;
        }

        /* .input-flex {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                align-items: center;
            } */

        input,
        textarea,
        select {
            border: 0;
            border-radius: 6px;
            padding: 2%;
            background-color: darkgray;
            margin: 10px;
            box-sizing: border-box;
            flex-grow: 1;
        }

        /* .container{
            background-image: linear-gradient(to bottom,blue,aqua);
            border-radius: 10px;

        } */
        .group {
            width: auto;
            text-align: center;
        }

        .group .power-container {
            background-color: #2E424D;
            width: 100%;
            height: 15px;
            border-radius: 5px;
        }

        .group .power-container #power-point {
            background-color: #D73F40;
            width: 1%;
            height: 100%;
            border-radius: 5px;
            transition: 0.5s;
        }
    </style>
    <div class="jumbotron  mt-2" style="padding:0px;">
        <div class="container">
            @if (Session::get('failed'))
        <div class="alert alert-danger">{{Session::get('failed')}}</div>
        @endif
            <h3><b>Penambahan Akun</b> </h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="{{ route('user.data') }}">DataAkun</a>/<a
                    href="#">TambahAkun</a></p>
        </div>
    </div>
    <form action="{{ route('user.store') }}" method="post" class="card bg-light mt-5 p-5">
        {{-- sebagai-token-akses-database --}}
        @csrf
        {{-- jika terjadi error di validasi, akan ditampilkan bagian error nya : --}}
        @if ($errors->any())
            <ul class="alert alert-danger p-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        {{-- jika berhasil munculkan notifnya : --}}
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        {{-- <div class="mb-3 row">
            <label for="name" class="col-sm-2 col-form-label">Nama User :</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="email" class="col-sm-2 col-form-label">Email :</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="email" name="email">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="password" class="col-sm-2 col-form-label">Password:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="password" name="password">
                <input type="text" class="form-control" id="password" name="password" value="1" disabled>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="role" class="col-sm-2 col-form-label">Role :</label>
            <div class="col-sm-10">
                <select id="role" class="form-control" name="role">
                    <option disabled hidden selected>Pilih</option>
                    <option value="admin">Admin</option>
                    <option value="user">user</option>
                </select>
            </div>
        </div>
        <div class="mb-3 row"> --}}
        {{-- <label for="stock" class="col-sm-2 col-form-label">Stock Awal:</label> --}}
        {{-- <div class="col-sm-10">
                <input type="number" class="form-control" id="stock" name="stock">
            </div> --}}
        {{-- </div> --}}
        {{-- <h3><i class="fa-regular fa-user pe-2">  </i>Personal Information</h3> --}}
        <div class="row mt-3">
            <div class="col-sm-6 w-75 ">
                <h3>Personal Information</h3>
                {{-- <h3><li style="list-style:disc;">Personal Information</li></h3> --}}
                {{-- <label for="role">Role</label> --}}
            </div>
            <div class="col-sm-6 w-25 ">
                <label for="role"><i class="fa-solid fa-list fa-user pe-2"></i>Role 
                    :</label>
                <select name="role" id="role" class="form-control">
                    <option value="user" selected>user</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
        </div>
        {{-- <div class="input-flex"> --}}
        <div class="row mt-3">
            <div class="col-sm-6 w-50 ">
                <input type="text" name="firstname" id="firstname" class="form-control w-100" placeholder="Firstname*" required>
            </div>
            <div class="col-sm-6 w-50 ">
                <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Lastname">
            </div>
            <div class="col-sm-6 w-50 ">
                <input type="text" name="email" id="email" class="form-control" placeholder="Email Address*" required>
            </div>
            <div class="col-sm-6 w-50 mb-2 ">
                <input type="text" name="notelp" id="notelp" class="form-control" placeholder="Phone Number*" required>
            </div>
        </div>
        {{-- </div> --}}
        <hr>
        {{-- <h3><i class="fa-regular fa-map pe-2">  </i>Billing Address</h3> --}}
        <h3>Billing Address</h3>
        {{-- <div class="input-flex"> --}}
        <div class="row mt-3">
            <div class="col-sm-6 w-50 ">
                <input type="text" name="company" id="company" class="form-control"
                    placeholder="Company Name (Optional)">
            </div>
            <div class="col-sm-6 w-50 ">
                <input type="text" name="address" id="address" class="form-control" placeholder="Street Address*" required>
            </div>
            <div class="col-sm-6 w-50 ">
                <input type="text" name="city" id="city" class="form-control" placeholder="City*" required>
            </div>
            <div class="col-sm-6 w-50 mb-2 ">
                <input type="text" name="state" id="state" class="form-control" placeholder="State*" required>
            </div>
            <div class="col-sm-6 w-100 mb-3" style="margin-right:100px">
                {{-- <input type="text" name="country" id="country" class="form-control" placeholder="Country"> --}}
                <select name="country" id="country" class="form-control">
                    <option value="Afghanistan">Afghanistan</option>
                    <option value="Albania">Albania</option>
                    <option value="Algeria">Algeria</option>
                    <option value="American Samoa">American Samoa</option>
                    <option value="Andorra">Andorra</option>
                    <option value="Angola">Angola</option>
                    <option value="Anguilla">Anguilla</option>
                    <option value="Antartica">Antarctica</option>
                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Armenia">Armenia</option>
                    <option value="Aruba">Aruba</option>
                    <option value="Australia">Australia</option>
                    <option value="Austria">Austria</option>
                    <option value="Azerbaijan">Azerbaijan</option>
                    <option value="Bahamas">Bahamas</option>
                    <option value="Bahrain">Bahrain</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="Barbados">Barbados</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Belgium">Belgium</option>
                    <option value="Belize">Belize</option>
                    <option value="Benin">Benin</option>
                    <option value="Bermuda">Bermuda</option>
                    <option value="Bhutan">Bhutan</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Bouvet Island">Bouvet Island</option>
                    <option value="Brazil">Brazil</option>
                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                    <option value="Bulgaria">Bulgaria</option>
                    <option value="Burkina Faso">Burkina Faso</option>
                    <option value="Burundi">Burundi</option>
                    <option value="Cambodia">Cambodia</option>
                    <option value="Cameroon">Cameroon</option>
                    <option value="Canada">Canada</option>
                    <option value="Cape Verde">Cape Verde</option>
                    <option value="Cayman Islands">Cayman Islands</option>
                    <option value="Central African Republic">Central African Republic</option>
                    <option value="Chad">Chad</option>
                    <option value="Chile">Chile</option>
                    <option value="China">China</option>
                    <option value="Christmas Island">Christmas Island</option>
                    <option value="Cocos Islands">Cocos (Keeling) Islands</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Comoros">Comoros</option>
                    <option value="Congo">Congo</option>
                    <option value="Congo">Congo, the Democratic Republic of the</option>
                    <option value="Cook Islands">Cook Islands</option>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Cota D'Ivoire">Cote d'Ivoire</option>
                    <option value="Croatia">Croatia (Hrvatska)</option>
                    <option value="Cuba">Cuba</option>
                    <option value="Cyprus">Cyprus</option>
                    <option value="Czech Republic">Czech Republic</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Dominica">Dominica</option>
                    <option value="Dominican Republic">Dominican Republic</option>
                    <option value="East Timor">East Timor</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="Egypt">Egypt</option>
                    <option value="El Salvador">El Salvador</option>
                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                    <option value="Eritrea">Eritrea</option>
                    <option value="Estonia">Estonia</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="Falkland Islands">Falkland Islands (Malvinas)</option>
                    <option value="Faroe Islands">Faroe Islands</option>
                    <option value="Fiji">Fiji</option>
                    <option value="Finland">Finland</option>
                    <option value="France">France</option>
                    <option value="France Metropolitan">France, Metropolitan</option>
                    <option value="French Guiana">French Guiana</option>
                    <option value="French Polynesia">French Polynesia</option>
                    <option value="French Southern Territories">French Southern Territories</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Gambia">Gambia</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Germany">Germany</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Gibraltar">Gibraltar</option>
                    <option value="Greece">Greece</option>
                    <option value="Greenland">Greenland</option>
                    <option value="Grenada">Grenada</option>
                    <option value="Guadeloupe">Guadeloupe</option>
                    <option value="Guam">Guam</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Guinea">Guinea</option>
                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                    <option value="Guyana">Guyana</option>
                    <option value="Haiti">Haiti</option>
                    <option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>
                    <option value="Holy See">Holy See (Vatican City State)</option>
                    <option value="Honduras">Honduras</option>
                    <option value="Hong Kong">Hong Kong</option>
                    <option value="Hungary">Hungary</option>
                    <option value="Iceland">Iceland</option>
                    <option value="India">India</option>
                    <option value="Indonesia" selected>Indonesia</option>
                    <option value="Iran">Iran (Islamic Republic of)</option>
                    <option value="Iraq">Iraq</option>
                    <option value="Ireland">Ireland</option>
                    <option value="Israel">Israel</option>
                    <option value="Italy">Italy</option>
                    <option value="Jamaica">Jamaica</option>
                    <option value="Japan">Japan</option>
                    <option value="Jordan">Jordan</option>
                    <option value="Kazakhstan">Kazakhstan</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Democratic People's Republic of Korea">Korea, Democratic People's Republic of
                    </option>
                    <option value="Korea">Korea, Republic of</option>
                    <option value="Kuwait">Kuwait</option>
                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                    <option value="Lao">Lao People's Democratic Republic</option>
                    <option value="Latvia">Latvia</option>
                    <option value="Lebanon">Lebanon</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberia">Liberia</option>
                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                    <option value="Liechtenstein">Liechtenstein</option>
                    <option value="Lithuania">Lithuania</option>
                    <option value="Luxembourg">Luxembourg</option>
                    <option value="Macau">Macau</option>
                    <option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Maldives">Maldives</option>
                    <option value="Mali">Mali</option>
                    <option value="Malta">Malta</option>
                    <option value="Marshall Islands">Marshall Islands</option>
                    <option value="Martinique">Martinique</option>
                    <option value="Mauritania">Mauritania</option>
                    <option value="Mauritius">Mauritius</option>
                    <option value="Mayotte">Mayotte</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Micronesia">Micronesia, Federated States of</option>
                    <option value="Moldova">Moldova, Republic of</option>
                    <option value="Monaco">Monaco</option>
                    <option value="Mongolia">Mongolia</option>
                    <option value="Montserrat">Montserrat</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Namibia">Namibia</option>
                    <option value="Nauru">Nauru</option>
                    <option value="Nepal">Nepal</option>
                    <option value="Netherlands">Netherlands</option>
                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                    <option value="New Caledonia">New Caledonia</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Niger">Niger</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Niue">Niue</option>
                    <option value="Norfolk Island">Norfolk Island</option>
                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                    <option value="Norway">Norway</option>
                    <option value="Oman">Oman</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Palau">Palau</option>
                    <option value="Panama">Panama</option>
                    <option value="Papua New Guinea">Papua New Guinea</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Peru">Peru</option>
                    <option value="Philippines">Philippines</option>
                    <option value="Pitcairn">Pitcairn</option>
                    <option value="Poland">Poland</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Puerto Rico">Puerto Rico</option>
                    <option value="Qatar">Qatar</option>
                    <option value="Reunion">Reunion</option>
                    <option value="Romania">Romania</option>
                    <option value="Russia">Russian Federation</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                    <option value="Saint LUCIA">Saint LUCIA</option>
                    <option value="Saint Vincent">Saint Vincent and the Grenadines</option>
                    <option value="Samoa">Samoa</option>
                    <option value="San Marino">San Marino</option>
                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="Senegal">Senegal</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Sierra">Sierra Leone</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Slovakia">Slovakia (Slovak Republic)</option>
                    <option value="Slovenia">Slovenia</option>
                    <option value="Solomon Islands">Solomon Islands</option>
                    <option value="Somalia">Somalia</option>
                    <option value="South Africa">South Africa</option>
                    <option value="South Georgia">South Georgia and the South Sandwich Islands</option>
                    <option value="Span">Spain</option>
                    <option value="SriLanka">Sri Lanka</option>
                    <option value="St. Helena">St. Helena</option>
                    <option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>
                    <option value="Sudan">Sudan</option>
                    <option value="Suriname">Suriname</option>
                    <option value="Svalbard">Svalbard and Jan Mayen Islands</option>
                    <option value="Swaziland">Swaziland</option>
                    <option value="Sweden">Sweden</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="Syria">Syrian Arab Republic</option>
                    <option value="Taiwan">Taiwan, Province of China</option>
                    <option value="Tajikistan">Tajikistan</option>
                    <option value="Tanzania">Tanzania, United Republic of</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Togo">Togo</option>
                    <option value="Tokelau">Tokelau</option>
                    <option value="Tonga">Tonga</option>
                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                    <option value="Tunisia">Tunisia</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Turkmenistan">Turkmenistan</option>
                    <option value="Turks and Caicos">Turks and Caicos Islands</option>
                    <option value="Tuvalu">Tuvalu</option>
                    <option value="Uganda">Uganda</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="United Arab Emirates">United Arab Emirates</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="United States">United States</option>
                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                    <option value="Uruguay">Uruguay</option>
                    <option value="Uzbekistan">Uzbekistan</option>
                    <option value="Vanuatu">Vanuatu</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="Vietnam">Viet Nam</option>
                    <option value="Virgin Islands (British)">Virgin Islands (British)</option>
                    <option value="Virgin Islands (U.S)">Virgin Islands (U.S.)</option>
                    <option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>
                    <option value="Western Sahara">Western Sahara</option>
                    <option value="Yemen">Yemen</option>
                    <option value="Serbia">Serbia</option>
                    <option value="Zambia">Zambia</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                </select>
            </div>
        </div>
        {{-- </div> --}}
        <hr>

        {{-- <h3><i class="fa-regular fa-exclamation pe-2" style="margin-left:10px;margin-right: 5px;">  </i>Account Security</h3> --}}
        <h3> Additional Information </br> <i style="font-size:18px;">(required fields are marked with *)
            </i></h3>
        <br>
        <div class="input-flex" style="margin-left:10px;">
            <div class="mb-3">

                {{-- <input type="password" name="password" id="password" class="form-control" placeholder="Password"> --}}
                <select name="money" id="money" class="form-control" style="padding-right: 300px;">
                    <option value="IDR">
                        <p><i class="fa-solid fa-file-lines pe-2"></i>💵 </p>IDR
                    </option>
                    <option value="USD">💲 USD</option>
                </select>
            </div>
        </div>
        <div class="row" id="contentPw" style="display:none;row-gap:inherit;">
            <h3>Password</h3>
            <div class="col-sm-6 w-50 " >
                <div class="group mt-3">
                    <input type="text" id="password" placeholder="Password*" name="password" class="form-control" />
                    {{-- <div class="btn" style="border-color:black;">
                         Generate Password
                     </div> --}}

                     <div class="group mt-3" style="margin-left:10px;">
                         <div class="power-container">
                             <div id="power-point"></div>
                         </div>
                         <label for="">
                             Strength of password <span id="persen">0%</span>
                         </label>
         
                     </div>
                    </div>
            </div>
            {{-- </div> --}}


            {{-- <div class="mb-3 mt-2" style="margin-right:200px"> --}}

            <div class="col-sm-6 w-50 " >
                <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                    placeholder="Confirm Password*" >
            </div>

            {{-- <div class="mb-3" hidden>
                <input hidden type="text" name="role" id="role" class="form-control"
                    placeholder="Confirm Password" value="user">
            </div> --}}

        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block mt-3" id="register">Register</button>
        <!-- Button trigger modal -->

        {{-- </div> --}}
    </form>

    {{-- 
        <button type="submit" class="btn btn-primary">Simpan Data</button>
    </form> --}}
@endsection

@push('script')
<script>
    
        
        // Add JavaScript to dynamically update the bulan dropdown based on the selected product
        document.getElementById('role').addEventListener('change', function() {
            var selectedRole = this.options[this.selectedIndex];
            let roleVal = selectedRole.value;
            let password = document.getElementById("contentPw");
            let btnRegister = document.getElementById("register");
            
            console.log(selectedRole,roleVal,password);
            if(roleVal === "admin"){
                password.style.display = "block";
                password.style.rowGap = "inherit";
            }else{
                password.style.display = "none";
            }

        });
</script>
    <script>
        // script.js 

        let password = document.getElementById("password");

        let persen = document.getElementById("persen");
        let power = document.getElementById("power-point");
        password.oninput = function() {
            let point = 0;
            let value = password.value;
            let widthPower = ["1%", "25%", "50%", "75%", "100%"];
            let colorPower = ["#D73F40", "#DC6551", "#F2B84F", "#BDE952", "#3ba62f"];

            if (value.length >= 6) {
                let arrayTest = [/[0-9]/, /[a-z]/, /[A-Z]/, /[^0-9a-zA-Z]/];
                arrayTest.forEach((item) => {
                    if (item.test(value)) {
                        point += 1;
                    }
                });
            }
            power.style.width = widthPower[point];
            power.style.backgroundColor = colorPower[point];
            persen.innerHTML = widthPower[point];
        };
    </script>
@endpush
