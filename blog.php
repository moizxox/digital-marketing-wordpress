<?php

/**
 * Template Name: Blog
 */

if (!defined('ABSPATH')) {
	exit;
}

$no_hero = true;

get_header();

the_post();

$guides = get_posts(array(
	'post_type' => 'guide',
	'posts_per_page' => -1
));

?>

<main class="main">
	
	<div class="container">
			<div class="works-about bdrs-5">
				<div class="row">
					<div class="col-lg-6">
						<div class="works-about__info">
															<h1 class="text-lg works-about__title">Best Articles</h1>
														<p><strong>Discover The Best Tools and Courses</strong><br>
Browse through our growing database of 1000+ Digital Marketing Tools, 800+ Digital Marketing Service providers and over 150 Digital Marketing Courses collected in a wide range of categories.</p>
<p><strong>Compare Your Options</strong><br>
Find the best Digital Marketing Tool or Digital Marketing Course or Digital Marketing Service Provider for your company using our comparison options.</p>
<p><strong>Grow Your Business</strong><br>
Choose the best Digital Marketing Tool that will offer all the features your company needs at an affordable price (or Free) and will allow you to take your business to the next level.</p>
													</div>
					</div>
					<div class="col-lg-6">
						<div class="works-about__image">
							<img src="https://digitalmarketingsupermarket.com/wp-content/themes/dm/images/how-it-works.png" srcset="https://digitalmarketingsupermarket.com/wp-content/themes/dm/images/how-it-works@2x.png 2x" alt="How it works">
						</div>
					</div>
				</div>
			</div>
		</div>
	
	
	
	
	<div class="container">
		
		<h2 class="category-title">{Category title}</h2>
		<div class="row">
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
		</div>
		
		
		
				
		<h2 class="category-title">{Category title}</h2>
		<div class="row">
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
		</div>
		
		
				
		<h2 class="category-title">{Category title}</h2>
		<div class="row">
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
			<div class="col-xl-4">
				<div class="item blog">
					   <div class="product-box">
						  <div class="product-box__image">
							 <a href="#"> <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2021/03/Mastering-Copywriting-Closing-Deals-ClosersCopy-ClosersCopy-1-480x306.png" alt=""></a>
						  </div>
						  <div class="product-box__info">
							 <h3 class="product-box__title"><a href="#">Search Intent and SEO: The Ultimate Guide</a></h3>
							 <div class="product-box__text"><p>{Author}</p> </div>
						  </div>
					   </div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
