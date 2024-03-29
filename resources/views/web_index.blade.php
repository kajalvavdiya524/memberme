@extends('layouts.validate.front')
@section('content')
    <!-- scrollToTop -->
<div class="scrollToTop"><i class="icon-up-open-big"></i></div>

    <!-- header start -->
        @include('layouts.validate.partials.front_header')
    <!-- header end -->

    <!-- banner start -->
        @include('layouts.validate.partials.front_banner')
    <!-- banner end -->

    <section class="hero-caption secPadding">

        <div class="container">

            <div class="row " style="margin-top: 0px;">
                <div class="col-sm-12">
                    <h2>Together<strong> Lets Start</strong> - <span>BUSINESS</span> with future perspective.</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure aperiam consequatur quo. Sed quis tortor magna. Maecenas hendrerit feugiat pulvinar. Aenean condimentum quam eu ultricies cursus.  Nulla facilisi. In hac habitasse platea dictumst. Ut nec tellus neque. Sed non dui eget arcu elementum facilisis.</p>
                </div>

            </div>

        </div>

    </section>
    <!-- section start -->
    <section class="section transprant-bg pclear secPadding">
        <div class="container no-view" data-animation-effect="fadeIn">
            <h1 id="services" class="title text-center">Services</h1>
            <div class="space"></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="media block-list">
                        <div class="media-left">
                            <i class="fa fa-trophy"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="media-heading">Member Management</h3>
                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure aperiam consequatur quo.</p>

                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="media block-list">
                        <div class="media-left">
                            <i class="fa fa-gear"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="media-heading">Subscriptions</h3>
                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure aperiam ducimus.</p>

                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="media block-list">
                        <div class="media-left">
                            <i class="fa fa-laptop"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="media-heading">Registrations</h3>
                            <blockquote>
                                <p>Dolor sit amet, consectetur adipisicing elit. Iure aperiam consequatur placeat.</p>

                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="media block-list">
                        <div class="media-left">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="media-heading">Communication</h3>
                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure aperiam consequatur.</p>

                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="media block-list">
                        <div class="media-left">
                            <i class="fa fa-heart"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="media-heading">Virtual Cards</h3>
                            <blockquote>
                                <p>Forem ipsum dolor sit amet, consectetur adipisicing elit. Iure aperiam consequatur.</p>

                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="media block-list">
                        <div class="media-left">
                            <i class="fa fa-magic"></i>
                        </div>
                        <div class="media-body">
                            <h3 class="media-heading">Card Printing</h3>
                            <blockquote>
                                <p>Norem ipsum dolor sit amet, consectetur adipisicing elit. Iure aperiam consequatur.</p>

                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- section end -->

    <!-- section start -->
    <section class="section clearfix no-view secPadding" data-animation-effect="fadeIn">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 id="about" class="title text-center">About <span>member me</span></h1>
                    <p class="lead text-center">member me has been designed to as a one stop portal for organisations to better engage with their members.</p>
                    <div class="space"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{asset('front/images/membership.jpg')}}" alt="">
                            <div class="space"></div>
                        </div>
                        <div class="col-md-6">
                            <p>Engage like never before with interactive modules.  The seamless and sleek interface making navigating a breeze</p>
                            <p>Automate process currently manual with seamless email and sms integration</p>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-arrow-circle-right pr-10 colored"></i> Lorem ipsum enimdolor sit amet</li>
                                <li><i class="fa fa-arrow-circle-right pr-10 colored"></i> Explicabo deleniti neque aliquid</li>
                                <li><i class="fa fa-arrow-circle-right pr-10 colored"></i> Consectetur adipisicing elit</li>
                                <li><i class="fa fa-arrow-circle-right pr-10 colored"></i> Lorem ipsum dolor sit amet</li>
                                <li><i class="fa fa-arrow-circle-right pr-10 colored"></i> Quo issimos molest quibusdam temporibus</li>
                            </ul>
                        </div>
                    </div>
                    <div class="space"></div>
                    <h2>Amazing free bootstrap template</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Lorem ipsum dolor sit amet, adipisicing  sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde error commodi architecto, laudantium culpa optio corporis quod earumdignissimos eius mollitia et quas officia doloremque.</p>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-arrow-circle-right pr-10 colored"></i> Lorem ipsum enimdolor sit amet</li>
                                <li><i class="fa fa-arrow-circle-right pr-10 colored"></i> Explicabo deleniti neque aliquid</li>
                                <li><i class="fa fa-arrow-circle-right pr-10 colored"></i> Consectetur adipisicing elit</li>
                                <li><i class="fa fa-arrow-circle-right pr-10 colored"></i> Lorem ipsum dolor sit amet</li>
                                <li><i class="fa fa-arrow-circle-right pr-10 colored"></i> Quo issimos molest quibusdam temporibus</li>
                            </ul>
                            <p>Dolores quam magnam aadipisicing  sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde molestias velit eveniet, facere autem saepe autrunt.</p>
                        </div>
                        <div class="col-md-6">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Collapsible Group Item #1
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            Consectetur adipisicing  sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earum pariatur omnis illo saepe numquam suscipit, nemo placeat ntium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earumdignissimos eius mollitia et quas officia doloremque ipsum labore rem deserunt.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Collapsible Group Item #2
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earum pariatur omnis illo saepe numquam suscipit, nemo placeat ntium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earumdignissimos eius mollitia et quas officia doloremque ipsum labore rem deserunt.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingThree">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                Collapsible Group Item #3
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                        <div class="panel-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi adipisci illo, voluptatum ipsam fuga error commodi architecto, laudantium culpa tenetur at id, beatae placeat deserunt iure quas voluptas fugit eveniet.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- section end -->

    <!-- section start -->
    <div class="default-bg colord secPadding">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h1 class="text-center">Amazing Free Bootstrap Template.</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- section end -->
    <section class="section secPadding" id="team">
        <div class="container">
            <h1 class="text-center title">Our Team</h1>
            <div class="separator"></div>
            <p class="lead text-center">Lorem ipsum dolor sit amet laudantium molestias simiut laboriosam.</p>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-xs-6 col-sm-3">
                            <div class="team__item">
                                <div class="team-item__img">
                                    <img src="{{asset('front/images/team2.jpg')}}" class="img-responsive" alt="...">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="team-item__name">Brent Thomson</div>
                                        <div class="team-item__position">Founder</div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="team-item__contact">
                                            <a class="team-item-contact__link" href="#">
                                                <i class="fa fa-twitter"></i>
                                            </a>
                                            <a class="team-item-contact__link team-item-contact__link_facebook" href="#">
                                                <i class="fa fa-facebook"></i>
                                            </a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div> <!-- / .row -->
                            </div> <!-- / .team__item -->
                        </div>

                        <div class="col-xs-6 col-sm-3">
                            <div class="team__item">
                                <div class="team-item__img">
                                    <img src="{{asset('front/images/team2.jpg')}}" class="img-responsive" alt="...">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="team-item__name">Mike Wilson</div>
                                        <div class="team-item__position">UI/UX Designer</div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="team-item__contact">
                                            <a class="team-item-contact__link" href="#">
                                                <i class="fa fa-twitter"></i>
                                            </a>
                                            <a class="team-item-contact__link team-item-contact__link_facebook" href="#">
                                                <i class="fa fa-facebook"></i>
                                            </a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div> <!-- / .row -->
                            </div> <!-- / .team__item -->
                        </div>

                        <div class="col-xs-6 col-sm-3">
                            <div class="team__item">
                                <div class="team-item__img">
                                    <img src="{{asset('front/images/team3.jpg')}}" class="img-responsive" alt="...">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="team-item__name">Vintel Mills</div>
                                        <div class="team-item__position">Project Manager</div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="team-item__contact">
                                            <a class="team-item-contact__link" href="#">
                                                <i class="fa fa-twitter"></i>
                                            </a>
                                            <a class="team-item-contact__link team-item-contact__link_facebook" href="#">
                                                <i class="fa fa-facebook"></i>
                                            </a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div> <!-- / .row -->
                            </div> <!-- / .team__item -->
                        </div>

                        <div class="col-xs-6 col-sm-3">
                            <div class="team__item">
                                <div class="team-item__img">
                                    <img src="{{asset('front/images/team4.jpg')}}" class="img-responsive" alt="...">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="team-item__name">James Resll</div>
                                        <div class="team-item__position">Software Developer</div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="team-item__contact">
                                            <a class="team-item-contact__link" href="#">
                                                <i class="fa fa-twitter"></i>
                                            </a>
                                            <a class="team-item-contact__link team-item-contact__link_facebook" href="#">
                                                <i class="fa fa-facebook"></i>
                                            </a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div> <!-- / .row -->
                            </div> <!-- / .team__item -->
                        </div>

                    </div> <!-- / .row -->
                </div>

            </div>
        </div>
    </section>
    <!-- section start -->
    <section class="section secPadding">
        <div class="container">
            <h1 class="text-center title" id="portfolio">Portfolio</h1>
            <div class="separator"></div>
            <p class="lead text-center">Lorem ipsum dolor sit amet laudantium molestias simi Quisquam incidunt.</p>
            <br>
            <div class="row no-view" data-animation-effect="fadeIn">
                <div class="col-md-12">

                    <!-- isotope filters start -->
                    <div class="filters text-center">
                        <ul class="nav nav-pills">
                            <li class="active"><a href="#" data-filter="*">All</a></li>
                            <li><a href="#" data-filter=".web-design">Web design</a></li>
                            <li><a href="#" data-filter=".app-development">App development</a></li>
                            <li><a href="#" data-filter=".mobile-apps">Mobile Apps</a></li>
                        </ul>
                    </div>
                    <!-- isotope filters end -->

                    <!-- portfolio items start -->
                    <div class="isotope-container row grid-space-20">
                        <div class="col-sm-6 col-md-3 isotope-item web-design">
                            <div class="image-box">
                                <div class="overlay-container">
                                    <img src="{{asset('front/images/portfolio-1.jpg')}}" alt="">
                                    <a class="overlay" data-toggle="modal" data-target="#project-1">
                                        <i class="fa fa-search-plus"></i>

                                    </a>
                                </div>
                                <a class="btn btn-default btn-block" data-toggle="modal" data-target="#project-1">Project Title</a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="project-1" tabindex="-1" role="dialog" aria-labelledby="project-1-label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="project-1-label">Project Title</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="{{asset('front/images/portfolio-1.jpg')}}" alt="">
                                                    <br/>
                                                    <h3>Project Description</h3>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earum pariatur omnis illo saepe numquam suscipit, nemo placeat dignissimos eius mollitia et quas officia doloremque ipsum labore rem deserunt.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal end -->
                        </div>

                        <div class="col-sm-6 col-md-3 isotope-item app-development">
                            <div class="image-box">
                                <div class="overlay-container">
                                    <img src="{{asset('front/images/portfolio-2.jpg')}}" alt="">
                                    <a class="overlay" data-toggle="modal" data-target="#project-2">
                                        <i class="fa fa-search-plus"></i>

                                    </a>
                                </div>
                                <a class="btn btn-default btn-block" data-toggle="modal" data-target="#project-2">Project Title</a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="project-2" tabindex="-1" role="dialog" aria-labelledby="project-2-label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="project-2-label">Project Title</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="{{asset('front/images/portfolio-2.jpg')}}" alt="">
                                                    <br/>
                                                    <h3>Project Description</h3>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earum pariatur omnis illo saepe numquam suscipit, nemo placeat dignissimos eius mollitia et quas officia doloremque ipsum labore rem deserunt.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal end -->
                        </div>

                        <div class="col-sm-6 col-md-3 isotope-item web-design">
                            <div class="image-box">
                                <div class="overlay-container">
                                    <img src="{{asset('front/images/portfolio-3.jpg')}}" alt="">
                                    <a class="overlay" data-toggle="modal" data-target="#project-3">
                                        <i class="fa fa-search-plus"></i>
                                    </a>
                                </div>
                                <a class="btn btn-default btn-block" data-toggle="modal" data-target="#project-3">Project Title</a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="project-3" tabindex="-1" role="dialog" aria-labelledby="project-3-label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="project-3-label">Project Title</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="{{asset('front/images/portfolio-3.jpg')}}" alt="">
                                                    <br/>
                                                    <h3>Project Description</h3>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earum pariatur omnis illo saepe numquam suscipit, nemo placeat dignissimos eius mollitia et quas officia doloremque ipsum labore rem deserunt.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal end -->
                        </div>

                        <div class="col-sm-6 col-md-3 isotope-item mobile-apps">
                            <div class="image-box">
                                <div class="overlay-container">
                                    <img src="{{asset('front/images/portfolio-4.jpg')}}" alt="">
                                    <a class="overlay" data-toggle="modal" data-target="#project-4">
                                        <i class="fa fa-search-plus"></i>
                                    </a>
                                </div>
                                <a class="btn btn-default btn-block" data-toggle="modal" data-target="#project-4">Project Title</a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="project-4" tabindex="-1" role="dialog" aria-labelledby="project-4-label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="project-4-label">Project Title</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="{{asset('front/images/portfolio-4.jpg')}}" alt="">
                                                    <br/>
                                                    <h3>Project Description</h3>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earum pariatur omnis illo saepe numquam suscipit, nemo placeat dignissimos eius mollitia et quas officia doloremque ipsum labore rem deserunt.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal end -->
                        </div>

                        <div class="col-sm-6 col-md-3 isotope-item app-development">
                            <div class="image-box">
                                <div class="overlay-container">
                                    <img src="{{asset('front/images/portfolio-5.jpg')}}" alt="">
                                    <a class="overlay" data-toggle="modal" data-target="#project-5">
                                        <i class="fa fa-search-plus"></i>
                                    </a>
                                </div>
                                <a class="btn btn-default btn-block" data-toggle="modal" data-target="#project-5">Project Title</a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="project-5" tabindex="-1" role="dialog" aria-labelledby="project-5-label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="project-5-label">Project Title</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="{{asset('front/images/portfolio-5.jpg')}}" alt="">
                                                    <br/>
                                                    <h3>Project Description</h3>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earum pariatur omnis illo saepe numquam suscipit, nemo placeat dignissimos eius mollitia et quas officia doloremque ipsum labore rem deserunt.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal end -->
                        </div>

                        <div class="col-sm-6 col-md-3 isotope-item web-design">
                            <div class="image-box">
                                <div class="overlay-container">
                                    <img src="{{asset('front/images/portfolio-6.jpg')}}" alt="">
                                    <a class="overlay" data-toggle="modal" data-target="#project-6">
                                        <i class="fa fa-search-plus"></i>
                                    </a>
                                </div>
                                <a class="btn btn-default btn-block" data-toggle="modal" data-target="#project-6">Project Title</a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="project-6" tabindex="-1" role="dialog" aria-labelledby="project-6-label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="project-6-label">Project Title</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="{{asset('front/images/portfolio-6.jpg')}}" alt="">
                                                    <br/>
                                                    <h3>Project Description</h3>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earum pariatur omnis illo saepe numquam suscipit, nemo placeat dignissimos eius mollitia et quas officia doloremque ipsum labore rem deserunt.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal end -->
                        </div>

                        <div class="col-sm-6 col-md-3 isotope-item mobile-apps">
                            <div class="image-box">
                                <div class="overlay-container">
                                    <img src="{{asset('front/images/portfolio-7.jpg')}}" alt="">
                                    <a class="overlay" data-toggle="modal" data-target="#project-7">
                                        <i class="fa fa-search-plus"></i>
                                        <span>Site Building</span>
                                    </a>
                                </div>
                                <a class="btn btn-default btn-block" data-toggle="modal" data-target="#project-7">Project Title</a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="project-7" tabindex="-1" role="dialog" aria-labelledby="project-7-label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="project-7-label">Project Title</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="{{asset('front/images/portfolio-7.jpg')}}" alt="">
                                                    <br/>
                                                    <h3>Project Description</h3>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earum pariatur omnis illo saepe numquam suscipit, nemo placeat dignissimos eius mollitia et quas officia doloremque ipsum labore rem deserunt.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal end -->
                        </div>

                        <div class="col-sm-6 col-md-3 isotope-item web-design">
                            <div class="image-box">
                                <div class="overlay-container">
                                    <img src="{{asset('front/images/portfolio-8.jpg')}}" alt="">
                                    <a class="overlay" data-toggle="modal" data-target="#project-8">
                                        <i class="fa fa-search-plus"></i>
                                    </a>
                                </div>
                                <a class="btn btn-default btn-block" data-toggle="modal" data-target="#project-8">Project Title</a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="project-8" tabindex="-1" role="dialog" aria-labelledby="project-8-label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="project-8-label">Project Title</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="{{asset('front/images/portfolio-8.jpg')}}" alt="">
                                                    <br/>
                                                    <h3>Project Description</h3>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earum pariatur omnis illo saepe numquam suscipit, nemo placeat dignissimos eius mollitia et quas officia doloremque ipsum labore rem deserunt.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal end -->
                        </div>

                        <div class="col-sm-6 col-md-3 isotope-item web-design">
                            <div class="image-box">
                                <div class="overlay-container">
                                    <img src="{{asset('front/images/portfolio-9.jpg')}}" alt="">
                                    <a class="overlay" data-toggle="modal" data-target="#project-9">
                                        <i class="fa fa-search-plus"></i>
                                    </a>
                                </div>
                                <a class="btn btn-default btn-block" data-toggle="modal" data-target="#project-9">Project Title</a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="project-9" tabindex="-1" role="dialog" aria-labelledby="project-9-label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="project-9-label">Project Title</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="{{asset('front/images/portfolio-9.jpg')}}" alt="">
                                                    <br/>
                                                    <h3>Project Description</h3>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earum pariatur omnis illo saepe numquam suscipit, nemo placeat dignissimos eius mollitia et quas officia doloremque ipsum labore rem deserunt.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal end -->
                        </div>

                        <div class="col-sm-6 col-md-3 isotope-item mobile-apps">
                            <div class="image-box">
                                <div class="overlay-container">
                                    <img src="{{asset('front/images/portfolio-10.jpg')}}" alt="">
                                    <a class="overlay" data-toggle="modal" data-target="#project-10">
                                        <i class="fa fa-search-plus"></i>
                                    </a>
                                </div>
                                <a class="btn btn-default btn-block" data-toggle="modal" data-target="#project-10">Project Title</a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="project-10" tabindex="-1" role="dialog" aria-labelledby="project-10-label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="project-10-label">Project Title</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="{{asset('front/images/portfolio-10.jpg')}}" alt="">
                                                    <br/>
                                                    <h3>Project Description</h3>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earum pariatur omnis illo saepe numquam suscipit, nemo placeat dignissimos eius mollitia et quas officia doloremque ipsum labore rem deserunt.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal end -->
                        </div>

                        <div class="col-sm-6 col-md-3 isotope-item web-design">
                            <div class="image-box">
                                <div class="overlay-container">
                                    <img src="{{asset('front/images/portfolio-11.jpg')}}" alt="">
                                    <a class="overlay" data-toggle="modal" data-target="#project-11">
                                        <i class="fa fa-search-plus"></i>
                                    </a>
                                </div>
                                <a class="btn btn-default btn-block" data-toggle="modal" data-target="#project-11">Project Title</a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="project-11" tabindex="-1" role="dialog" aria-labelledby="project-11-label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="project-11-label">Project Title</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="{{asset('front/images/portfolio-11.jpg')}}" alt="">
                                                    <br/>
                                                    <h3>Project Description</h3>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earum pariatur omnis illo saepe numquam suscipit, nemo placeat dignissimos eius mollitia et quas officia doloremque ipsum labore rem deserunt.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal end -->
                        </div>

                        <div class="col-sm-6 col-md-3 isotope-item app-development">
                            <div class="image-box">
                                <div class="overlay-container">
                                    <img src="{{asset('front/')}}images/portfolio-12.jpg" alt="">
                                    <a class="overlay" data-toggle="modal" data-target="#project-12">
                                        <i class="fa fa-search-plus"></i>
                                    </a>
                                </div>
                                <a class="btn btn-default btn-block" data-toggle="modal" data-target="#project-12">Project Title</a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="project-12" tabindex="-1" role="dialog" aria-labelledby="project-12-label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="project-12-label">Project Title</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <img src="{{asset('front/')}}images/portfolio-12.jpg" alt="">
                                                    <br/>
                                                    <h3>Project Description</h3>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque sed, quidem quis praesentium, ut unde. Quae sed, incidunt laudantium nesciunt, optio corporis quod earum pariatur omnis illo saepe numquam suscipit, nemo placeat dignissimos eius mollitia et quas officia doloremque ipsum labore rem deserunt.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal end -->
                        </div>

                    </div>
                    <!-- portfolio items end -->

                </div>
            </div>
        </div>
    </section>
    <!-- section end -->
    <!-- section start -->
    <section class="default-bg secPadding">

        <div class="container">
            <div class="row">
                <div class='col-md-offset-2 col-md-8 text-center'>
                    <h2>Clients Testimonials</h2>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-offset-2 col-md-8'>
                    <div class="carousel slide" data-ride="carousel" id="quote-carousel">
                        <!-- Bottom Carousel Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#quote-carousel" data-slide-to="0" class="active"></li>
                            <li data-target="#quote-carousel" data-slide-to="1"></li>
                            <li data-target="#quote-carousel" data-slide-to="2"></li>
                        </ol>

                        <!-- Carousel Slides / Quotes -->
                        <div class="carousel-inner">

                            <!-- Quote 1 -->
                            <div class="item active">
                                <blockquote>
                                    <div class="row">
                                        <div class="col-sm-3 text-center">
                                            <img class="img-circle" src="https://s3.amazonaws.com/uifaces/faces/twitter/kolage/128.jpg" style="width: 100px;height:100px;">
                                        </div>
                                        <div class="col-sm-9">
                                            <p>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit!</p>
                                            <small>Someone famous</small>
                                        </div>
                                    </div>
                                </blockquote>
                            </div>
                            <!-- Quote 2 -->
                            <div class="item">
                                <blockquote>
                                    <div class="row">
                                        <div class="col-sm-3 text-center">
                                            <img class="img-circle" src="https://s3.amazonaws.com/uifaces/faces/twitter/mijustin/128.jpg" style="width: 100px;height:100px;">
                                        </div>
                                        <div class="col-sm-9">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam auctor nec lacus ut tempor. Mauris.</p>
                                            <small>Someone famous</small>
                                        </div>
                                    </div>
                                </blockquote>
                            </div>
                            <!-- Quote 3 -->
                            <div class="item">
                                <blockquote>
                                    <div class="row">
                                        <div class="col-sm-3 text-center">
                                            <img class="img-circle" src="https://s3.amazonaws.com/uifaces/faces/twitter/keizgoesboom/128.jpg" style="width: 100px;height:100px;">
                                        </div>
                                        <div class="col-sm-9">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut rutrum elit in arcu blandit, eget pretium nisl accumsan. Sed ultricies commodo tortor, eu pretium mauris.</p>
                                            <small>Someone famous</small>
                                        </div>
                                    </div>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- section end -->
    <section id="price" class="price-table secPadding">
        <div class="container text-center">
            <div class="heading">
                <h1 class="text-center title" id="">Our Pricing</h1>
                <div class="separator"></div>
                <p class="lead text-center">Validate member me is provided as software as a service with monthly subscription plans below.</p>
                <br>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="panel panel-default text-center">
                        <div class="panel-heading">
                            <h3>Basic</h3>
                        </div>
                        <div class="panel-body">
                            <h3 class="panel-title price"><span class="price-cents">$20.00</span><span class="price-month"></span></h3>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item">< 100 Members</li>
                            <li class="list-group-item">50mb of Storage</li>
                            <li class="list-group-item">1 User</li>
                            <li class="list-group-item">10 GB Bandwidth</li>
                            <li class="list-group-item">Security Suite</li>
                            <li class="list-group-item"><a class="btn btn-default">Sign Up Now!</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="panel panel-default text-center">
                        <div class="panel-heading">
                            <h3>Starter</h3>
                        </div>
                        <div class="panel-body">
                            <h3 class="panel-title price"><span class="price-cents">$35.00</span><span class="price-month"></span></h3>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item">< 250 Members</li>
                            <li class="list-group-item">100mb of Storage</li>
                            <li class="list-group-item">3 Users</li>
                            <li class="list-group-item">25 GB Bandwidth</li>
                            <li class="list-group-item">Security Suite</li>
                            <li class="list-group-item"><a class="btn btn-default">Sign Up Now!</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="panel panel-danger text-center">
                        <div class="panel-heading">
                            <h3>Premium</h3>
                        </div>
                        <div class="panel-body">
                            <h3 class="panel-title price"><span class="price-cents">$60.00</span><span class="price-month"></span></h3>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item">< 500 Members</li>
                            <li class="list-group-item">200mb of Storage</li>
                            <li class="list-group-item">5 Users</li>
                            <li class="list-group-item">100 GB Bandwidth</li>
                            <li class="list-group-item">Security Suite</li>
                            <li class="list-group-item"><a class="btn btn-primary">Sign Up Now!</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="panel panel-default text-center">
                        <div class="panel-heading">
                            <h3>Ultimate</h3>
                        </div>
                        <div class="panel-body">
                            <h3 class="panel-title price"><span class="price-cents">$105.00</span><span class="price-month"></span></h3>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item">< 1000 Members</li>
                            <li class="list-group-item">250mb of Storage</li>
                            <li class="list-group-item">Unlimited</li>
                            <li class="list-group-item">500 GB Bandwidth</li>
                            <li class="list-group-item">Security Suite</li>
                            <li class="list-group-item"><a class="btn btn-default">Sign Up Now!</a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- footer start -->
    <footer id="footer">

        <!-- .footer start -->
        <div class="footer section">
            <div class="container">
                <h1 class="title text-center" id="contact">Contact Us</h1>
                <div class="space"></div>
                <div class="row">

                    <div class="col-sm-6">
                        <div class="footer-content">


                            <!--NOTE: Update your email Id in "contact_me.php" file in order to receive emails from your contact form-->

                            <form name="sentMessage" id="contactForm"  novalidate>
                                <h3>Contact Form</h3>
                                <div class="control-group">
                                    <div class="controls">
                                        <input type="text" class="form-control"
                                               placeholder="Full Name" id="name" required
                                               data-validation-required-message="Please enter your name" />
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <input type="email" class="form-control" placeholder="Email"
                                               id="email" required
                                               data-validation-required-message="Please enter your email" />
                                    </div>
                                </div>

                                <div class="control-group">
                                    <div class="controls">
										 <textarea rows="10" cols="100" class="form-control"
                                                   placeholder="Message" id="message" required
                                                   data-validation-required-message="Please enter your message" minlength="5"
                                                   data-validation-minlength-message="Min 5 characters"
                                                   maxlength="999" style="resize:none"></textarea>
                                    </div>
                                </div>
                                <div id="success"> </div> <!-- For success/fail messages -->
                                <button type="submit" class="btn btn-default pull-right">Send</button><br />
                            </form>

                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="footer-content">


                            <div class="widget-content">

                                <p>We are here to answer any queries you have. For any assistance or pre sales questions flick us a message and we will get right back to you</p><br/>

                                <p class="contacts"><i class="fa fa-map-marker"></i>Level 1, 8 Raroa Road, Lower Hutt</p>

                                <p class="contacts"><i class="fa fa-phone"></i> +64 27 432 0662</p>

                                <p class="contacts"><i class="fa fa-envelope"></i> support@validate.co.nz</p>



                            </div>

                            </aside>
                            <ul class="social-links">
                                <li class="facebook"><a target="_blank" href="#"><i class="fa fa-facebook"></i></a></li>
                                <li class="twitter"><a target="_blank" href="#"><i class="fa fa-twitter"></i></a></li>
                                <li class="googleplus"><a target="_blank" href="#"><i class="fa fa-google-plus"></i></a></li>
                                <li class="skype"><a target="_blank" href="#"><i class="fa fa-skype"></i></a></li>
                                <li class="linkedin"><a target="_blank" href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li class="youtube"><a target="_blank" href="#"><i class="fa fa-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- .footer end -->

        <!-- .subfooter start -->
        <div class="subfooter">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-center">Copyright © 2017 <a target="_blank" href="http://www.validate.co.nz">Validate NZ Ltd</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- .subfooter end -->

    </footer>
    <!-- footer end -->

@endsection
