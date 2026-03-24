@extends('user dashboard.dashboard')
@section('title', 'Vibe and burn | FAQs')
@section('content')
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="components-preview wide-md mx-auto">
                                   <!-- .nk-block-head -->
                                    <div class="nk-block nk-block-lg">
                                        <div class="nk-block-head">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title">FAQ</h4>
                                                <div class="nk-block-des">
                                                </div>
                                            </div>
                                        </div>
                                   <div class="card card-preview mb-2">
                                            <div class="card-inner">
                                            <div id="accordion">

  <div class="card">
    <div class="card-header">
      <a class="card-link text-dark font-weight-bold" data-toggle="collapse" href="#collapseOne">
        What is Partial status?
      </a>
    </div>
    <div id="collapseOne" class="collapse show" data-parent="#accordion">
      <div class="card-body">
        Partial Status is when we partially refund the remains of an order. Sometimes for some reasons we are unable to deliver a full order, so we refund you the remaining undelivered amount. Example: You bought an order with quantity 10 000 and charges 10$, let's say we delivered 9 000 and the remaining 1 000 we couldn't deliver, then we will "Partial" the order and refund you the remaining 1 000 (1$ in this example)
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <a class="collapsed card-link text-dark font-weight-bold" data-toggle="collapse" href="#collapseTwo">
        What is Drip Feed?
      </a>
    </div>
    <div id="collapseTwo" class="collapse" data-parent="#accordion">
      <div class="card-body">
Drip Feed is a service that we are offering so you would be able to put the same order multiple times automatically.
Example: let's say you want to get 1000 likes on your Instagram Post but you want to get 100 likes each 30 minutes, you will put Link: Your Post Link Quantity: 100 Runs: 10 (as you want to run this order 10 times, if you want to get 2000 likes, you will run it 20 times, etc&hellip;) Interval:30 (because you want to get 100 likes on your post each 30 minutes, if you want each hour, you will put 60 because the time is in minutes)
P.S: Never order more quantity than the maximum which is written on the service name (Quantity x Runs),Example if the service's max is 4000, you don&rsquo;t put Quantity: 500 and Run: 10,because total quantity will be 500x10 = 5000 which is bigger than the service max (4000).
Also never put the Interval below the actual start time (some services need 60 minutes to start, don&rsquo;t put Interval less than the service start time or it will cause a fail in your order).
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <a class="collapsed card-link text-dark font-weight-bold" data-toggle="collapse" href="#collapseThree">
        How do I use mass order?
      </a>
    </div>
    <div id="collapseThree" class="collapse" data-parent="#accordion">
      <div class="card-body">
        You put the service ID followed by | followed by the link followed by | followed by quantity on each line To get the service ID of a service please check here: https://smmfollows.com/services Let&rsquo;s say you want to use the Mass Order to add Instagram Followers to your 3 accounts: abcd, asdf, qwer From the Services List @ https://smmfollows.com/services, the service ID for this service Instagram Followers [5K]; is 187 Lets say you want to add 1000 followers for each account, the output will be like this: ID|Link|Quantity or in this example: 187|abcd|1000 187|asdf|1000 187|qwer|1000
      </div>
    </div>
  </div>

   <div class="card">
    <div class="card-header">
      <a class="collapsed card-link text-dark font-weight-bold" data-toggle="collapse" href="#collapseFour">
        I want a panel like yours / I want to resell your services how?
      </a>
    </div>
    <div id="collapseFour" class="collapse" data-parent="#accordion">
      <div class="card-body">
        To get a panel like ours, please check https://bit.ly/2EejTNn to rent a panel, and then you can connect to us via API easily! </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header">
      <a class="collapsed card-link text-dark font-weight-bold" data-toggle="collapse" href="#collapseFive">
        Cancel button / Refill button is not working for me?
    </a>
    </div>
    <div id="collapseFive" class="collapse" data-parent="#accordion">
      <div class="card-body">
        The cancel or refill button sends a trigger to cancel or refill an order, it doesn't work instantly, it's just a trigger, sometimes it's too late to stop an order, and sometimes an order might not need refill.
    </div>
  </div>
</div>

  <div class="card">
    <div class="card-header">
      <a class="collapsed card-link text-dark font-weight-bold" data-toggle="collapse" href="#collapseSix">
        Can i get a discount?
      </a>
    </div>
    <div id="collapseSix" class="collapse" data-parent="#accordion">
      <div class="card-body">
Yes we can offer it if you are a big buyer</div>
  </div>
</div>

  <div class="card">
    <div class="card-header">
      <a class="collapsed card-link text-dark font-weight-bold" data-toggle="collapse" href="#collapseSeven">
        How to get youtube comment link?
      </a>
    </div>
    <div id="collapseSeven" class="collapse" data-parent="#accordion">
      <div class="card-body">
        Find the timestamp that is located next to your username above your comment (for example: "3 days ago") and hover over it then right click and "Copy Link Address". The link will be something like this: https://www.youtube.com/watch?v=12345&amp;lc=a1b21etc instead of just https://www.youtube.com/watch?v=12345 To be sure that you got the correct link, paste it in your browser's address bar and you will see that the comment is now the first one below the video and it says "Highlighted comment".
        </div></div>
    </div>

  <div class="card">
    <div class="card-header">
      <a class="collapsed card-link text-dark font-weight-bold" data-toggle="collapse" href="#collapseEight">
        What is "Instagram mentions", how do you use it?
      </a>
    </div>
    <div id="collapseEight" class="collapse" data-parent="#accordion">
      <div class="card-body">
Instagram Mention is when you mention someone on Instagram (example @abcde this means you have mentioned abcde under this post and abcde will receive a notification to check the post). Basically the Instagram Mentions [User Followers], you put the link of your post, and the username of the person that you want us to mention HIS FOLLOWERS!  </div>
  </div></div>

  <div class="card">
    <div class="card-header">
      <a class="collapsed card-link text-dark font-weight-bold" data-toggle="collapse" href="#collapseNine">
        The link must be added before the user goes live or after?
      </a>
    </div>
    <div id="collapseNine" class="collapse" data-parent="#accordion">
      <div class="card-body">
        After he goes live, or just 5 second before he goes!</div>
  </div>

</div>

<div class="card">
    <div class="card-header">
      <a class="collapsed card-link text-dark font-weight-bold" data-toggle="collapse" href="#collapseTen">
        What is "Instagram Saves", and what does it do?
      </a>
    </div>
    <div id="collapseTen" class="collapse" data-parent="#accordion">
      <div class="card-body">
        Instagram Saves is when a user saves a post to his history on Instagram (by pressing the save button near the like button). A lot of saves for a post increase its impression.
        </div>
  </div>

</div>
                                            </div>
                                        </div><!-- .card-preview -->
                                    </div>
                                </div><!-- .components-preview -->
                            </div>
                        </div>
                   @endsection