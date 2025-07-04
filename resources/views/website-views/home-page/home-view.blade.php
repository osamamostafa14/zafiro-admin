<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Type some info">
  <meta name="author" content="Type name">

  <title>Winji</title>

  <link href="{{asset('public/assets/admin')}}/css/bootstrap.css?v=2.0" rel="stylesheet" type="text/css" />

  <!-- Custom css -->
  <link href="{{asset('public/assets/admin')}}/css/ui.css?v=2.0" rel="stylesheet" type="text/css" />
  <link href="{{asset('public/assets/admin')}}/css/responsive.css?v=2.0" rel="stylesheet" type="text/css" />

  <!-- Font awesome 5 -->
  <link href="{{asset('public/assets/admin')}}/fonts/fontawesome/css/all.min.css" type="text/css" rel="stylesheet">

</head>
<body>

<header class="section-header">	
	<section class="header-main">
		<div class="container">
			<div class="row gy-3 align-items-center">
			<div class="col-lg-2 col-sm-4 col-4">
					<a href="http://bootstrap-ecommerce.com" class="navbar-brand">
						<img class="logo" height="40" src="{{asset('public/assets/admin')}}/images/logo.png">
					</a> <!-- brand end.// -->
					<a href="http://bootstrap-ecommerce.com" class="navbar-brand">
						<img class="logo" height="30" style="margin-left:12px" src="{{asset('public/assets/admin')}}/images/logo-text.png">
					</a>
					
					
				
				</div>
				<div class="order-lg-last col-lg-5 col-sm-8 col-8">
					<div class="float-end">
						<a href="#" class="btn btn-light"> 
						    <i class="fa fa-user"></i>  <span class="ms-1 d-none d-sm-inline-block">Sign in  </span> 
						</a>
						<a href="#" class="btn btn-light"> 
							<i class="fa fa-heart"></i>  <span class="ms-1 d-none d-sm-inline-block">Wishlist</span>   
						</a>
						<a data-bs-toggle="offcanvas" href="#offcanvas_cart" class="btn btn-light"> 
							<i class="fa fa-shopping-cart"></i> <span class="ms-1">My cart </span> 
						</a>
			        </div>
				</div> <!-- col end.// -->
				<div class="col-lg-5 col-md-12 col-12">
					<form action="#" class="">
		              <div class="input-group">
		                <input type="search" class="form-control" style="width:55%" placeholder="Search">
		                <select class="form-select">
		                  <option value="">All type</option>
		                  <option value="codex">Special</option>
		                  <option value="comments">Only best</option>
		                  <option value="content">Latest</option>
		                </select>
		                <button class="btn btn-primary"> <i class="fa fa-search"></i> </button>
		              </div> <!-- input-group end.// -->
		            </form>
				</div> <!-- col end.// -->
				
			</div> <!-- row end.// -->
		</div> <!-- container end.// -->
	</section> <!-- header-main end.// -->

	<nav class="navbar navbar-light bg-gray-light navbar-expand-lg">
		<div class="container">
			<button class="navbar-toggler border" type="button" data-bs-toggle="collapse" data-bs-target="#navbar_main">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="navbar_main">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link ps-0" href="#"> Categories </a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Hot offers</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Gift boxes</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Projects</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Menu item</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Menu name</a>
					</li>
					<li class="nav-item dropdown">
						<a class="dropdown-toggle nav-link" href="#" data-bs-toggle="dropdown">
							Others
						</a>
						<ul class="dropdown-menu">
							<li> <a class="dropdown-item" href="#">Submenu one </a> </li>
							<li> <a class="dropdown-item" href="#">Submenu two</a> </li>
							<li> <a class="dropdown-item" href="#">Submenu three</a> </li>
						</ul>
					</li>
				</ul>
			</div> <!-- collapse end.// -->
		</div> <!-- container end.// -->
	</nav> <!-- navbar end.// -->
</header> <!-- section-header end.// -->

<!-- ================ SECTION INTRO ================ -->
<section class="section-intro pt-3">
	<div class="container">

		<div class="row gx-3">
			<main class="col-lg-9">
				<article class="card-banner p-5 bg-primary" style="height: 350px">
					<div style="max-width: 500px">
			        <h2 class="text-white">Great products with <br> best deals </h2>
			        <p class="text-white">No matter how far along you are in your sophistication as an amateur astronomer, there is always one.</p>
			        <a href="#" class="btn btn-warning"> View more </a>
			        </div>
				</article>
			</main>
			<aside class="col-lg-3">
				<article class="card-banner h-100" style="background-color: var(--bs-warning)">
					<div class="card-body text-center">
			        	<h5 class="mt-3 text-white">Amazing Gifts</h5>
			       		<p class="text-white">No matter how far along you are in your sophistication</p>
			        	<a href="#" class="btn btn-outline-light"> View more </a>
			        </div>
				</article>
			</aside>
		</div> <!-- row //end -->

	</div> <!-- container end.// -->
</section>
<!-- ================ SECTION INTRO END.// ================ -->

<!-- ================ SECTION CATEGORY ================ -->

<section class="padding-top">
<div class="container">
	<nav class="row gy-4 row-cols-xl-8 row-cols-sm-4 row-cols-3">
      <div class="col">
        <a href="#" class="item-link text-center">
            <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/sofa.svg">
           	</span>
           <span class="text"> Interior items </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/ball.svg">
           	</span>
           <span class="text"> Sport and travel </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/diamond.svg">
           </span>
           <span class="text"> Jewellery </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/watch.svg">
           </span>
           <span class="text"> Accessories </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/car.svg">
           </span>
           <span class="text"> Automobiles </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/homeitem.svg">
           </span>
           <span class="text"> Home items </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/music.svg">
           	</span>
           <span class="text"> Musical items </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/book.svg">
           	</span>
           <span class="text"> Books, reading </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/toy.svg">
           </span>
           <span class="text"> Kid's toys </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/animal.svg">
           </span>
           <span class="text"> Pet items </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/shirt.svg">
           </span>
           <span class="text"> Menâ€™s clothing </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/shoe-man.svg">
           </span>
           <span class="text"> Menâ€™s clothing </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/phone.svg">
           </span>
           <span class="text"> Smartphones </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/fix.svg">
           </span>
           <span class="text"> Tools </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/education.svg">
           </span>
           <span class="text"> Education </span>
        </a>
      </div> <!-- col.// -->
      <div class="col">
        <a href="#" class="item-link text-center">
           <span class="icon mb-2 icon-md rounded"> 
           		<img width="32" height="32" src="images/icons/category-svg-blue/warehouse.svg">
           </span>
           <span class="text"> Other items </span>
        </a>
      </div> <!-- col.// -->
   </nav>
</div>	
</section>
<!-- ================ SECTION CATEGORY END.// ================ -->

<!-- ================ SECTION PRODUCTS ================ -->
<section class="padding-y">
<div class="container">

	<header class="section-heading">
		<h3 class="section-title">New products</h3>
	</header> 

        
	<div class="row">
	     @foreach($products as $product)
        @php($productPrice = $product->price)
     
        @if($product->discount_type = 'percent')
           @php($productDiscount = $productPrice * ($product->discount/100))
          @elseif($product->discount_type = 'amount')
           @php($productDiscount = $productPrice - $productPrice)
        @endif
		<div class="col-lg-3 col-md-6 col-sm-6">
		    
		    
			<figure class="card-product-grid">
				<a href="#" class="img-wrap rounded bg-gray-light">
				    @if($product->discount >0 || $product->discount_type >0) 
				<!-- New product.// 	<span class="topbar"> <span class="badge bg-danger"> New </span> </span> -->
						<span class="topbar"> <span class="badge bg-warning"> Offer </span> </span>
						@endif
					<img height="250" class="mix-blend-multiply" src="{{asset('storage/app/public/product')}}/{{$product['image']}}"> 
			
				</a>
				<figcaption class="pt-2">
					<a href="#" class="float-end btn btn-light btn-icon"> <i class="fa fa-heart"></i> </a>
					<strong class="price">{{$productPrice - $productDiscount}} EGP</strong> 
					@if($product->discount > 0)
					<p class="text-decoration-line-through">{{$productPrice}}</p><!-- price.// -->
					@endif
					<a href="#" class="title text-truncate">{{$product->name}}</a>
					<!-- Variation.//	<small class="text-muted">Strawberry Lip balm</small>  -->
				</figcaption>
			</figure>
		</div> 
		@endforeach


	</div> <!-- row end.// -->

</div> <!-- container end.// -->
</section>
<!-- ================ SECTION PRODUCTS END.// ================ -->


<!-- ================ SECTION FEATURE ================ -->
<section>
<div class="container">
	<article class="card p-4" style="background-color: var(--bs-primary)">
	  <div class="row align-items-center">
	    <div class="col"> 
	      <h4 class="mb-0 text-white">Best products and brands in store</h4>
	      <p class="mb-0 text-white-50">Trendy products and text to build on the card title</p>
	    </div>
	    <div class="col-auto"> <a class="btn btn-warning" href="#">Discover</a> </div>
	  </div>
	</article>
</div> <!-- container end.// -->
</section>
<!-- ================ SECTION FEATURE END.// ================ -->

<!-- ================ SECTION PRODUCTS ================ -->
<section class="padding-y">
<div class="container">

	<header class="section-heading">
		<h3 class="section-title">Recommended</h3>
	</header> 

	<div class="row">
		<div class="col-lg-3 col-md-6 col-sm-6">
			<figure class="card-product-grid">
				<a href="#" class="img-wrap rounded bg-gray-light"> 
					<img height="250" class="mix-blend-multiply" src="images/items/9.jpg"> 
				</a>
				<figcaption class="pt-2">
					<a href="#" class="float-end btn btn-light btn-icon"> <i class="fa fa-heart"></i> </a>
					<strong class="price">$17.00</strong> <!-- price.// -->
					<a href="#" class="title text-truncate">Blue jeans shorts for men</a>
					<small class="text-muted">Sizes: S, M, XL</small>
				</figcaption>
			</figure>
		</div> <!-- col end.// -->

		<div class="col-lg-3 col-md-6 col-sm-6">
			<figure class="card-product-grid">
				<a href="#" class="img-wrap rounded bg-gray-light"> 
					<img height="250" class="mix-blend-multiply" src="images/items/10.jpg"> 
				</a>
				<figcaption class="pt-2">
					<a href="#" class="float-end btn btn-light btn-icon"> <i class="fa fa-heart"></i> </a>
					<strong class="price">$9.50</strong> <!-- price.// -->
					<a href="#" class="title text-truncate">Slim fit T-shirt for men</a>
					<small class="text-muted">Sizes: S, M, XL</small>
				</figcaption>
			</figure>
		</div> <!-- col end.// -->

		<div class="col-lg-3 col-md-6 col-sm-6">
			<figure class="card-product-grid">
				<a href="#" class="img-wrap rounded bg-gray-light"> 
					<img height="250" class="mix-blend-multiply" src="images/items/11.jpg"> 
				</a>
				<figcaption class="pt-2">
					<a href="#" class="float-end btn btn-light btn-icon"> <i class="fa fa-heart"></i> </a>
					<strong class="price">$29.95</strong> <!-- price.// -->
					<a href="#" class="title text-truncate">Modern product name here</a>
					<small class="text-muted">Sizes: S, M, XL</small>
				</figcaption>
			</figure>
		</div> <!-- col end.// -->

		<div class="col-lg-3 col-md-6 col-sm-6">
			<figure class="card-product-grid">
				<a href="#" class="img-wrap rounded bg-gray-light"> 
					<img height="250" class="mix-blend-multiply" src="images/items/12.jpg"> 
				</a>
				<figcaption class="pt-2">
					<a href="#" class="float-end btn btn-light btn-icon"> <i class="fa fa-heart"></i> </a>
					<strong class="price">$29.95</strong> <!-- price.// -->
					<a href="#" class="title text-truncate">Modern product name here</a>
					<small class="text-muted">Sizes: S, M, XL</small>
				</figcaption>
			</figure>
		</div> <!-- col end.// -->
	</div> <!-- row end.// -->

</div> <!-- container end.// -->
</section>
<!-- ================ SECTION PRODUCTS END.// ================ -->

<section class="padding-y-sm bg-gray-light">
  <div class="container">
      <div class="row gy-3 align-items-center">
        <div class="col-md-4">
          <form>
            <div class="input-group">
              <input class="form-control" type="text" placeholder="Email">
              <button class="btn btn-light" type="submit"> Subscribe  </button>
            </div> <!-- input-group.// -->
          </form>
        </div>
        <div class="col-md-8">
          <nav class="float-lg-end">
              <a class="btn btn-icon btn-light" title="Facebook" target="_blank" href="#"><i class="fab fa-facebook-f"></i></a>
              <a class="btn btn-icon btn-light" title="Instagram" target="_blank" href="#"><i class="fab fa-instagram"></i></a>
              <a class="btn btn-icon btn-light" title="Youtube" target="_blank" href="#"><i class="fab fa-youtube"></i></a>
              <a class="btn btn-icon btn-light" title="Twitter" target="_blank" href="#"><i class="fab fa-twitter"></i></a>
          </nav>
        </div>
      </div> <!-- row.// -->
  </div><!-- //container -->
</section>

<footer class="section-footer bg-white border-top">
<div class="container">
  <section class="footer-main padding-y">
    <div class="row">
      <aside class="col-12 col-sm-12 col-lg-4">
        <article class="me-lg-4">
          <img src="images/logo.png" class="logo-footer">
          <p class="mt-3"> Â© 2018- 2021 Templatemount. <br> â€¨All rights reserved. </p>
        </article>
      </aside>
      <aside class="col-6 col-sm-4 col-lg-2">
        <h6 class="titl">Store</h6>
        <ul class="list-menu mb-3">
          <li> <a href="#">About us</a></li>
          <li> <a href="#">Find store</a></li>
          <li> <a href="#">Categories</a></li>
          <li> <a href="#">Blogs</a></li>
        </ul>
      </aside>
      <aside class="col-6 col-sm-4 col-lg-2">
        <h6 class="title">Information</h6>
        <ul class="list-menu mb-3">
          <li> <a href="#">Help center</a></li>
          <li> <a href="#">Money refund</a></li>
          <li> <a href="#">Shipping info</a></li>
          <li> <a href="#">Refunds</a></li>
        </ul>
      </aside>
      <aside class="col-6 col-sm-4  col-lg-2">
        <h6 class="title">Support</h6>
        <ul class="list-menu mb-3">
          <li> <a href="#"> Help center </a></li>
          <li> <a href="#"> Documents </a></li>
          <li> <a href="#"> Account restore </a></li>
          <li> <a href="#"> My Orders </a></li>
        </ul>
      </aside>
      <aside class="col-6 col-sm-4 col-lg-2">
        <h6 class="title">Our apps</h6>
        <a href="#" class="mb-2 d-inline-block"> <img src="images/misc/btn-appstore.png" height="38"></a>
        <a href="#" class="mb-2 d-inline-block"> <img src="images/misc/btn-market.png" height="38"></a>
      </aside>
    </div> <!-- row.// -->
  </section>  <!-- footer-top.// -->

  <section class="footer-bottom d-flex justify-content-lg-between border-top">
    <div>
      <i class="fab fa-lg fa-cc-visa"></i>
      <i class="fab fa-lg fa-cc-amex"></i>
      <i class="fab fa-lg fa-cc-mastercard"></i>
      <i class="fab fa-lg fa-cc-paypal"></i>
    </div>
    <nav class="dropup">
        <button class="dropdown-toggle btn d-flex align-items-center py-0" type="button" data-bs-toggle="dropdown">
          <img src="images/flags/flag-usa.png" class="me-2" height="20"> 
          <span>English</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#">Russian</a></li>
          <li><a class="dropdown-item" href="#">Arabic</a></li>
          <li><a class="dropdown-item" href="#">Spanish</a></li>
        </ul>
    </nav>
    
  </section>
</div> <!-- container end.// -->
</footer>

<!-- Bootstrap js -->
<script src="js/bootstrap.bundle.min.js"></script>

<!-- Custom js -->
<script src="js/script.js?v=2.0"></script>

</body>
</html>