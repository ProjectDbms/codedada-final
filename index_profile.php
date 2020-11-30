<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>CodeDada - Your One stop solution for coding</title>
		<?php
			session_start();
			include("includes/db_connection.php");
			if(!(isset($_SESSION["username"]))) {
				//header("location: login.php");
			}
			include("includes/header.php");
		?>
        
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
        <!-- Font Awesome icons -->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="./assets/css/index_profile.css" rel="stylesheet" />
	
	
</head>
<body id="page-top">
        <!-- Masthead-->
        
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script> -->
        <?php include("includes/navbar.php"); ?>
        <section class="section2 bg-light" >
            <div class="container">
                <!-- Row 1-->
                <div class="row align-items-center  mb-4 mb-lg-5">
                    <div class="col-xl-5 col-lg-7"><img class="img-fluid mb-3 mb-lg-0" src="./assets/images/developer-coding.svg" alt="" /></div>
                    <div class="col-xl-3 col-lg-5">
                        <div class="featured-text">
                            <h1>Welcome!!</h1>
                            <h4><?php echo $_SESSION['username'] ?></h4>
                            <p class="text-black-50 mb-0">
                            <?php
                                $username = $_SESSION['username'];
                                $sql = "SELECT * FROM accounts WHERE username='$username'";
                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_assoc($result);
                                echo $row['email_id'];
                            ?>
                            </p>
                        </div>
                    </div>
                </div>
                        <h1 class="text-center">Your TimeLine</h1>
                <div class="container">
                
                <div class="row justify-content-center no-gutters mb-5 mb-lg-0">
                        
                    <!-- <div class="col-lg-12"><img class="img-fluid" src="./assets/images/hire.jpg" alt="" /></div> -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>
                <canvas id="myChart" width="600" height="300"></canvas>
                <?php
                    $username = $_SESSION['username'];
                    $sql = "SELECT * FROM  rank WHERE username='$username' ORDER BY timestamp ASC";
                    $result = mysqli_query($conn, $sql);

                    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    $user_ranks = array();
                    foreach($row as $rank_row) {
                        $temp_row = array();
                        $year = date("Y, m", strtotime($rank_row['timestamp']));
                        $rank = $rank_row['rank'];
                        $temp_row['year'] = $year;
                        $temp_row['rank'] = $rank;
                        $user_ranks[] = $temp_row;
                    }
                    $rank_len = count($user_ranks);
                ?>
                <script>
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var options = {
                        responsive: true,
                            title: {
                            display: true,
                            position: "top",
                            text: "Contest History",
                            fontSize: 18,
                            fontColor: "#111"
                        },
                        legend: {
                            display: true,
                            position: "bottom",
                            labels: {
                                fontColor: "#333",
                                fontSize: 16
                            }
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    suggestedMin: 1,
                                    suggestedMax: 100
                                }
                            }]
                        }
                    };
                    
                    var lbl = new Array();
                    var dat = new Array();
                    var pausecontent = <?php echo json_encode($user_ranks); ?>;
                    
                    for(var i=0; i<parseInt("<?php echo $rank_len ?>"); i++) {
                        lbl[i] = pausecontent[i]['year'];
                        dat[i] = pausecontent[i]['rank'];
                        if(dat[i] <= 0) {
                            dat[i] = 1;
                        } else {
                            dat[i] = 101 - dat[i];
                        }
                    }
                    var lineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: lbl,
                            datasets: [
                                {
                                    label: "2020",
                                    data: dat,
                                    backgroundColor: "blue",
                                    borderColor: "#17a2b8",
                                    fill: false,
                                    lineTension: 0,
                                    radius: 5
                                }
                            ]
                        },
                        options: options
                    });
                    </script>
                    
                </div>
                <!--Row 3-->
                <div class="row justify-content-center no-gutters">
                    <div class="col-lg-6"><img class="img-fluid" src="./assets/images/code_discuss.png" alt="" /></div>
                    <div class="col-lg-6 order-lg-first">
                        <div class="bg-white text-center h-100 project">
                            <div class="d-flex h-100">
                                <div class="project-text w-100 my-auto text-center text-lg-right">
                                    <h4 class="text-black">Competitive Coding</h4>
                                    <p class="mb-0 text-black-50">Learn the competitive coding with codedada.com and work as a team</p>
                                    <hr class="d-none d-lg-block mb-0 mr-0" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                </div>
                <br>
       <br>
       <br>
       <br>
                </section>
       
        <!-- Contact-->
        <section class="contact-section bg-black" id="contact">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="card py-4 h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-map-marked-alt text-primary mb-2"></i>
                                <h4 class="text-uppercase m-0">Address</h4>
                                <hr class="my-4" />
                                <div class="small text-black-50">somewhere in the cloud</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="card py-4 h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-envelope text-primary mb-2"></i>
                                <h4 class="text-uppercase m-0">Email</h4>
                                <hr class="my-4" />
                                <div class="small text-black-50"><a href="#!">hello@codedada.com</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="card py-4 h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-mobile-alt text-primary mb-2"></i>
                                <h4 class="text-uppercase m-0">Phone</h4>
                                <hr class="my-4" />
                                <div class="small text-black-50">9616123719872391</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="social d-flex justify-content-center">
                    <a class="mx-2" href="#!"><i class="fab fa-twitter"></i></a>
                    <a class="mx-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                    <a class="mx-2" href="#!"><i class="fab fa-github"></i></a>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="footer bg-black small text-center text-white-50"><div class="container">Copyright © codeDada.com 2020</div></footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <!-- Core theme JS-->
        <script src="./assets/js/index_profile.js"></script>
    </body>
	</html>