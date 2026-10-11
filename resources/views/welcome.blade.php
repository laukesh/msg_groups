@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
 <section class="sect-cover">
	<div class="container">
	  <div class="main-content">
		<div class="section">
			
			  <section class="metrics-wrapper">
				<div class="section-label">Pre-launch at a glance</div>
				
				<div class="metrics-grid">
				  <div class="metric-card">
					<h4>Units reserved</h4>
					<div class="value">58 / 62</div>
					<div class="sub">94% of leasable units</div>
				  </div>
				  <div class="metric-card">
					<h4>Reservation deposit</h4>
					<div class="value">34</div>
					<div class="sub"><b>$83,000</b> collected (1 mo)</div>
				  </div>
				  <div class="metric-card">
					<h4>Pre-handover deposit</h4>
					<div class="value">4</div>
					<div class="sub"><b>$23,200</b> collected (2 mo)</div>
				  </div>
				  <div class="metric-card">
					<h4>In fit-out now</h4>
					<div class="value">6</div>
					<div class="sub">works underway</div>
				  </div>
				  <div class="metric-card">
					<h4>Handover complete</h4>
					<div class="value">4</div>
					<div class="sub">units handed over</div>
				  </div>
				  <div class="metric-card">
					<h4>Deposits collected</h4>
					<div class="value">$106,200</div>
					<div class="sub">of $451,200 expected</div>
				  </div>
				</div>

				<div class="section-label">Fit-out pipeline</div>
				
				<div class="pipeline-container">
				  <button class="stage-item">
					<div class="stage-head">
					  <span class="stage-badge">–</span>
					  <span class="stage-index">START</span>
					</div>
					<div class="stage-total">24</div>
					<div class="stage-label">Awaiting deposit</div>
					<div class="stage-units">24 units</div>
				  </button>

				  <button class="stage-item">
					<div class="stage-head">
					  <span class="stage-badge">1</span>
					  <span class="stage-index">STAGE 1</span>
					</div>
					<div class="stage-total">16</div>
					<div class="stage-label">Reservation paid</div>
					<div class="stage-units">16 units</div>
				  </button>

				  <button class="stage-item">
					<div class="stage-head">
					  <span class="stage-badge">2</span>
					  <span class="stage-index">STAGE 2</span>
					</div>
					<div class="stage-total">8</div>
					<div class="stage-label">Design approved</div>
					<div class="stage-units">8 units</div>
				  </button>

				  <button class="stage-item">
					<div class="stage-head">
					  <span class="stage-badge">3</span>
					  <span class="stage-index">STAGE 3</span>
					</div>
					<div class="stage-total">10</div>
					<div class="stage-label">Contractor approved</div>
					<div class="stage-units">10 units</div>
				  </button>

				  <button class="stage-item">
					<div class="stage-head">
					  <span class="stage-badge">4</span>
					  <span class="stage-index">STAGE 4</span>
					</div>
					<div class="stage-total">6</div>
					<div class="stage-label">Fit-out in progress</div>
					<div class="stage-units">6 units</div>
				  </button>

				  <button class="stage-item">
					<div class="stage-head">
					  <span class="stage-badge">5</span>
					  <span class="stage-index">STAGE 5</span>
					</div>
					<div class="stage-total">4</div>
					<div class="stage-label">Handover</div>
					<div class="stage-units">4 units</div>
				  </button>
				</div>
				<div class="pipeline-info">Each unit moves left to right, from reservation deposit through to handover. Tap a stage to see those units.</div>

				<div class="section-label">Deposits &amp; floors</div>
				
				<div class="columns-layout">
				  <div class="content-box">
					<h3>Deposit collection</h3>
					<p>Total deposit per unit = 3 months' rent (1 month reservation + 2 months pre-handover).</p>
					
					<div>
					  <div class="payment-row">
						<div class="payment-header">
						  <div>
							<div class="payment-name">Reservation deposit</div>
							<div class="payment-sub">1 month · locks the unit</div>
						  </div>
						  <div class="payment-val">$83,000</div>
						</div>
						<div class="progress-track">
						  <i class="fill-paid" style="width:58.62%"></i>
						  <i class="fill-overdue" style="width:10.34%"></i>
						  <i class="fill-unpaid" style="width:31.04%"></i>
						</div>
						<div class="status-legend">
						  <span><i style="background:#006D5B"></i>34 paid</span>
						  <span><i style="background:#C0392B"></i>6 overdue</span>
						  <span><i style="background:#F5A300;opacity:.55"></i>18 not paid</span>
						</div>
					  </div>

					  <div class="payment-row">
						<div class="payment-header">
						  <div>
							<div class="payment-name">Pre-handover deposit</div>
							<div class="payment-sub">2 months · due before opening</div>
						  </div>
						  <div class="payment-val">$23,200</div>
						</div>
						<div class="progress-track">
						  <i class="fill-paid" style="width:6.9%"></i>
						  <i class="fill-overdue" style="width:0%"></i>
						  <i class="fill-unpaid" style="width:93.1%"></i>
						</div>
						<div class="status-legend">
						  <span><i style="background:#006D5B"></i>4 paid</span>
						  <span><i style="background:#C0392B"></i>0 overdue</span>
						  <span><i style="background:#F5A300;opacity:.55"></i>54 not paid</span>
						</div>
					  </div>
					</div>
				  </div>

				  <div class="content-box">
					<h3>Units by floor</h3>
					<p>Reserved vs total leasable units on each level.</p>
					
					<div>
					  <div class="floor-item">
						<div class="floor-title">
						  Ground Floor
						  <div class="floor-code">G/1</div>
						</div>
						<div class="floor-bar"><i style="width:96.88%"></i></div>
						<div class="floor-count"><b>31</b> / 32 reserved</div>
					  </div>

					  <div class="floor-item">
						<div class="floor-title">
						  First Floor
						  <div class="floor-code">L/2</div>
						</div>
						<div class="floor-bar"><i style="width:88.89%"></i></div>
						<div class="floor-count"><b>16</b> / 18 reserved</div>
					  </div>

					  <div class="floor-item">
						<div class="floor-title">
						  Second Floor
						  <div class="floor-code">L/3</div>
						</div>
						<div class="floor-bar"><i style="width:91.67%"></i></div>
						<div class="floor-count"><b>11</b> / 12 reserved</div>
					  </div>
					</div>
				  </div>
				</div>
			  </section>

			 
		</div>
	  </div>
	</div>
</section> 
@endsection
