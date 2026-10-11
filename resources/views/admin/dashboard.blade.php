@extends('layouts.app')

@section('title','Admin Dashboard')

@section('content')
 <section class="sect-cover">
	<div class="container">
	  <div class="main-content">
		<div class="section">
			
			{{-- =========================================================
			     DASHBOARD KPI SUMMARY
			========================================================= --}}

			<div class="dashboard-kpi-grid">


			    {{-- =====================================================
			         TOTAL ASSET VALUE
			    ====================================================== --}}

			    <div class="dashboard-kpi-card">

			        <div class="kpi-icon kpi-icon-blue">

			            <i class="ri-building-4-line"></i>

			        </div>


			        <div class="kpi-content">

			            <div class="kpi-label">
			                Total Asset Value
			            </div>

			            <div class="kpi-value">
			                {{ $totalAssetValue ?? '$0.00 M' }}
			            </div>

			            <div class="kpi-change positive">

			                <i class="ri-arrow-up-line"></i>

			                {{ $assetValueChange ?? '0%' }}

			                <span>vs Apr 2024</span>

			            </div>

			        </div>

			    </div>


			    {{-- =====================================================
			         TOTAL GLA
			    ====================================================== --}}

			    <div class="dashboard-kpi-card">

			        <div class="kpi-icon kpi-icon-green">

			            <i class="ri-layout-grid-line"></i>

			        </div>


			        <div class="kpi-content">

			            <div class="kpi-label">
			                Total GLA
			            </div>

			            <div class="kpi-value">
			                {{ $totalGla ?? '0 m²' }}
			            </div>

			            <div class="kpi-change positive">

			                <i class="ri-arrow-up-line"></i>

			                {{ $glaChange ?? '0%' }}

			                <span>vs Apr 2024</span>

			            </div>

			        </div>

			    </div>


			    {{-- =====================================================
			         OCCUPANCY RATE
			    ====================================================== --}}

			    <div class="dashboard-kpi-card">

			        <div class="kpi-icon kpi-icon-purple">

			            <i class="ri-pie-chart-2-line"></i>

			        </div>


			        <div class="kpi-content">

			            <div class="kpi-label">
			                Occupancy Rate
			            </div>

			            <div class="kpi-value">
			                {{ $occupancyRate ?? '0%' }}
			            </div>

			            <div class="kpi-change positive">

			                <i class="ri-arrow-up-line"></i>

			                {{ $occupancyChange ?? '0%' }}

			                <span>vs Apr 2024</span>

			            </div>

			        </div>

			    </div>


			    {{-- =====================================================
			         ANNUAL REVENUE
			    ====================================================== --}}

			    <div class="dashboard-kpi-card">

			        <div class="kpi-icon kpi-icon-yellow">

			            <i class="ri-money-rupee-circle-line"></i>

			        </div>


			        <div class="kpi-content">

			            <div class="kpi-label">
			                Annual Revenue
			            </div>

			            <div class="kpi-value">
			                {{ $annualRevenue ?? '$0.00 M' }}
			            </div>

			            <div class="kpi-change positive">

			                <i class="ri-arrow-up-line"></i>

			                {{ $revenueChange ?? '0%' }}

			                <span>vs Apr 2024</span>

			            </div>

			        </div>

			    </div>


			    {{-- =====================================================
			         NET OPERATING INCOME
			    ====================================================== --}}

			    <div class="dashboard-kpi-card">

			        <div class="kpi-icon kpi-icon-cyan">

			            <i class="ri-wallet-3-line"></i>

			        </div>


			        <div class="kpi-content">

			            <div class="kpi-label">
			                Net Operating Income
			            </div>

			            <div class="kpi-value">
			                {{ $netOperatingIncome ?? '$0.00 M' }}
			            </div>

			            <div class="kpi-change positive">

			                <i class="ri-arrow-up-line"></i>

			                {{ $noiChange ?? '0%' }}

			                <span>vs Apr 2024</span>

			            </div>

			        </div>

			    </div>


			    {{-- =====================================================
			         PORTFOLIO ROI
			    ====================================================== --}}

			    <div class="dashboard-kpi-card">

			        <div class="kpi-icon kpi-icon-red">

			            <i class="ri-focus-3-line"></i>

			        </div>


			        <div class="kpi-content">

			            <div class="kpi-label">
			                Portfolio ROI
			            </div>

			            <div class="kpi-value">
			                {{ $portfolioRoi ?? '0%' }}
			            </div>

			            <div class="kpi-change positive">

			                <i class="ri-arrow-up-line"></i>

			                {{ $roiChange ?? '0%' }}

			                <span>vs Apr 2024</span>

			            </div>

			        </div>

			    </div>


			</div>

			<div class="dashboard-three-column-grid">

			    {{-- Portfolio Performance --}}
			    <div class="dashboard-chart-grid">


				    {{-- =====================================================
				         PORTFOLIO PERFORMANCE
				    ====================================================== --}}

				    <div class="dashboard-panel portfolio-performance-panel">

				        <div class="dashboard-panel-header">

				            <div>

				                <h5 class="dashboard-panel-title">
				                    Portfolio Performance
				                </h5>

				                <span class="dashboard-panel-subtitle">
				                    Revenue, NOI, expenses and profit
				                </span>

				            </div>


				            <div class="dashboard-panel-actions">

				                <select
				                    class="dashboard-period-select"
				                    id="portfolioPerformancePeriod"
				                >

				                    <option value="monthly">
				                        Monthly
				                    </option>

				                    <option value="quarterly">
				                        Quarterly
				                    </option>

				                    <option value="yearly">
				                        Yearly
				                    </option>

				                </select>

				            </div>

				        </div>


				        {{-- LEGEND --}}

				        <div class="performance-legend">

				            <div class="performance-legend-item">

				                <span class="legend-dot revenue"></span>

				                Revenue

				            </div>


				            <div class="performance-legend-item">

				                <span class="legend-dot noi"></span>

				                NOI

				            </div>


				            <div class="performance-legend-item">

				                <span class="legend-dot expense"></span>

				                Operating Expense

				            </div>


				            <div class="performance-legend-item">

				                <span class="legend-dot profit"></span>

				                Net Profit

				            </div>

				        </div>


				        {{-- CHART --}}

				        <div class="portfolio-chart-container">

				            <canvas id="portfolioPerformanceChart"></canvas>

				        </div>

				    </div>


				</div>


			    {{-- Development Pipeline --}}
			    <div class="dashboard-pipeline-panel dashboard-panel">

				    <div class="dashboard-panel-header">

				        <div>

				            <h5 class="dashboard-panel-title">
				                Development Pipeline
				            </h5>

				            <span class="dashboard-panel-subtitle">
				                Projects by development stage
				            </span>

				        </div>

				        <a
				            href=""
				            class="dashboard-view-all"
				        >
				            View All
				        </a>

				    </div>


				    <div class="development-pipeline">


				        {{-- LAND ACQUISITION --}}

				        <a
				            href=""
				            class="pipeline-stage pipeline-stage-land"
				        >

				            <div class="pipeline-shape">

				                <span>
				                    Land Acquisition
				                </span>

				            </div>

				            <div class="pipeline-count">

				                {{ $pipelineLand ?? 3 }}

				            </div>

				        </a>


				        {{-- FEASIBILITY --}}

				        <a
				            href=""
				            class="pipeline-stage pipeline-stage-feasibility"
				        >

				            <div class="pipeline-shape">

				                <span>
				                    Feasibility
				                </span>

				            </div>

				            <div class="pipeline-count">

				                {{ $pipelineFeasibility ?? 2 }}

				            </div>

				        </a>


				        {{-- DESIGN --}}

				        <a
				            href=""
				            class="pipeline-stage pipeline-stage-design"
				        >

				            <div class="pipeline-shape">

				                <span>
				                    Design
				                </span>

				            </div>

				            <div class="pipeline-count">

				                {{ $pipelineDesign ?? 4 }}

				            </div>

				        </a>


				        {{-- PROCUREMENT --}}

				        <a
				            href=""
				            class="pipeline-stage pipeline-stage-procurement"
				        >

				            <div class="pipeline-shape">

				                <span>
				                    Procurement
				                </span>

				            </div>

				            <div class="pipeline-count">

				                {{ $pipelineProcurement ?? 3 }}

				            </div>

				        </a>


				        {{-- CONSTRUCTION --}}

				        <a
				            href=""
				            class="pipeline-stage pipeline-stage-construction"
				        >

				            <div class="pipeline-shape">

				                <span>
				                    Construction
				                </span>

				            </div>

				            <div class="pipeline-count">

				                {{ $pipelineConstruction ?? 5 }}

				            </div>

				        </a>


				        {{-- HANDOVER --}}

				        <a
				            href=""
				            class="pipeline-stage pipeline-stage-handover"
				        >

				            <div class="pipeline-shape">

				                <span>
				                    Handover
				                </span>

				            </div>

				            <div class="pipeline-count">

				                {{ $pipelineHandover ?? 2 }}

				            </div>

				        </a>


				    </div>


				    {{-- TOTAL --}}

				    <div class="pipeline-total">

				        <span>
				            Total Projects
				        </span>

				        <strong>
				            {{ $pipelineTotal ?? 19 }}
				        </strong>

				    </div>

				</div>


			    {{-- =========================================================
				     PORTFOLIO BY ASSET TYPE
				========================================================= --}}

				<div class="dashboard-panel asset-type-panel">

				    <div class="dashboard-panel-header">

				        <div>

				            <h5 class="dashboard-panel-title">
				                Portfolio by Asset Type
				            </h5>

				            <span class="dashboard-panel-subtitle">
				                Distribution of portfolio value
				            </span>

				        </div>

				        <a
				            href=""
				            class="dashboard-view-all"
				        >
				            View All
				        </a>

				    </div>


				    <div class="asset-type-content">


				        {{-- DONUT --}}

				        <div class="asset-type-chart">

				            <canvas id="portfolioAssetTypeChart"></canvas>

				            <div class="asset-type-total">

				                <span>
				                    Total Asset Value
				                </span>

				                <strong>
				                    {{ $totalAssetValue ?? '$185.00 M' }}
				                </strong>

				            </div>

				        </div>


				        {{-- LEGEND --}}

				        <div class="asset-type-legend">


				            {{-- RETAIL --}}

				            <div class="asset-type-item">

							    <span class="asset-type-dot retail"></span>

							    <div class="asset-type-name">

							        <span>
							            Retail <small>40.6%</small>
							        </span>

							        <strong>
							            $75.20M
							        </strong>

							    </div>

							</div>


				            {{-- RESIDENTIAL --}}

				            <div class="asset-type-item">

							    <span class="asset-type-dot residential"></span>

							    <div class="asset-type-name">

							        <span>
							            Residential <small>24.6%</small>
							        </span>

							        <strong>
							            $45.60M
							        </strong>

							    </div>

							</div>


				            {{-- OFFICE --}}

				            <div class="asset-type-item">

							    <span class="asset-type-dot office"></span>

							    <div class="asset-type-name">

							        <span>
							            Office <small>15.5%</small>
							        </span>

							        <strong>
							            $28.70M
							        </strong>

							    </div>

							</div>


				            {{-- HOSPITALITY --}}

				            <div class="asset-type-item">

							    <span class="asset-type-dot hospitality"></span>

							    <div class="asset-type-name">

							        <span>
							            Hospitality <small>9.9%</small>
							        </span>

							        <strong>
							            $18.30M
							        </strong>

							    </div>

							</div>


				            {{-- INDUSTRIAL --}}

				            <div class="asset-type-item">

							    <span class="asset-type-dot industrial"></span>

							    <div class="asset-type-name">

							        <span>
							            Industrial <small>9.3%</small>
							        </span>

							        <strong>
							            $17.20M
							        </strong>

							    </div>

							</div>


				        </div>

				    </div>

				</div>

			</div>


			{{-- =========================================================
			 DASHBOARD ROW
			 PROJECT RISK + LEASE EXPIRY + RENTAL COLLECTION
			========================================================= --}}

			<div class="dashboard-three-column-grid">


			{{-- =====================================================
			     PROJECTS AT RISK
			====================================================== --}}

			<div class="dashboard-grid-item">

			    <div class="dashboard-panel projects-risk-panel">

			        <div class="dashboard-panel-header">

			            <div>

			                <h5 class="dashboard-panel-title">
			                    Projects at Risk
			                </h5>

			                <span class="dashboard-panel-subtitle">
			                    Projects requiring management attention
			                </span>

			            </div>

			            <a
			                href=""
			                class="dashboard-view-all"
			            >
			                View All
			            </a>

			        </div>


			        <div class="projects-risk-table-wrapper">

			            <table class="projects-risk-table">

			                <thead>

			                    <tr>
			                        <th>Project</th>
			                        <th>Progress</th>
			                        <th>Schedule</th>
			                        <th>Budget</th>
			                        <th>Risk</th>
			                    </tr>

			                </thead>


			                <tbody>

			                    <tr>

			                        <td>
			                            <a
			                                href=""
			                                class="risk-project-name"
			                            >
			                                Mall of Downtown
			                            </a>
			                        </td>

			                        <td>

			                            <div class="project-progress">

			                                <div class="project-progress-track">

			                                    <div
			                                        class="project-progress-bar"
			                                        style="width:72%;"
			                                    ></div>

			                                </div>

			                                <span>72%</span>

			                            </div>

			                        </td>

			                        <td>
			                            <span class="variance-negative">
			                                -12d
			                            </span>
			                        </td>

			                        <td>
			                            <span class="variance-negative">
			                                +3.2%
			                            </span>
			                        </td>

			                        <td>
			                            <span class="risk-badge risk-high">
			                                High
			                            </span>
			                        </td>

			                    </tr>


			                    <tr>

			                        <td>
			                            <a
			                                href=""
			                                class="risk-project-name"
			                            >
			                                Green Residences
			                            </a>
			                        </td>

			                        <td>

			                            <div class="project-progress">

			                                <div class="project-progress-track">

			                                    <div
			                                        class="project-progress-bar"
			                                        style="width:88%;"
			                                    ></div>

			                                </div>

			                                <span>88%</span>

			                            </div>

			                        </td>

			                        <td>
			                            <span class="variance-negative">
			                                -8d
			                            </span>
			                        </td>

			                        <td>
			                            <span class="variance-warning">
			                                +1.5%
			                            </span>
			                        </td>

			                        <td>
			                            <span class="risk-badge risk-medium">
			                                Medium
			                            </span>
			                        </td>

			                    </tr>


			                    <tr>

			                        <td>
			                            <a
			                                href=""
			                                class="risk-project-name"
			                            >
			                                Business Tower
			                            </a>
			                        </td>

			                        <td>

			                            <div class="project-progress">

			                                <div class="project-progress-track">

			                                    <div
			                                        class="project-progress-bar"
			                                        style="width:41%;"
			                                    ></div>

			                                </div>

			                                <span>41%</span>

			                            </div>

			                        </td>

			                        <td>
			                            <span class="variance-negative">
			                                -18d
			                            </span>
			                        </td>

			                        <td>
			                            <span class="variance-negative">
			                                +7.8%
			                            </span>
			                        </td>

			                        <td>
			                            <span class="risk-badge risk-high">
			                                High
			                            </span>
			                        </td>

			                    </tr>


			                    <tr>

			                        <td>
			                            <a
			                                href=""
			                                class="risk-project-name"
			                            >
			                                Hotel & Suites
			                            </a>
			                        </td>

			                        <td>

			                            <div class="project-progress">

			                                <div class="project-progress-track">

			                                    <div
			                                        class="project-progress-bar"
			                                        style="width:65%;"
			                                    ></div>

			                                </div>

			                                <span>65%</span>

			                            </div>

			                        </td>

			                        <td>
			                            <span class="variance-negative">
			                                -5d
			                            </span>
			                        </td>

			                        <td>
			                            <span class="variance-warning">
			                                +2.4%
			                            </span>
			                        </td>

			                        <td>
			                            <span class="risk-badge risk-medium">
			                                Medium
			                            </span>
			                        </td>

			                    </tr>

			                </tbody>

			            </table>

			        </div>

			    </div>

			</div>



			{{-- =====================================================
			     LEASE EXPIRY SUMMARY
			====================================================== --}}

			<div class="dashboard-grid-item">

			    <div class="dashboard-panel lease-expiry-panel">

			        <div class="dashboard-panel-header">

			            <div>

			                <h5 class="dashboard-panel-title">
			                    Lease Expiry Summary
			                </h5>

			                <span class="dashboard-panel-subtitle">
			                    Upcoming lease expirations
			                </span>

			            </div>

			            <a
			                href=""
			                class="dashboard-view-all"
			            >
			                View All
			            </a>

			        </div>


			        <div class="lease-expiry-chart-wrapper">

			            <canvas id="leaseExpiryChart"></canvas>

			        </div>


			        <div class="lease-expiry-summary">


			            <div class="lease-expiry-summary-item">

			                <span class="lease-expiry-dot expiry-0-3"></span>

			                <div>

			                    <strong>
			                        {{ $leaseExpiry0To3 ?? 18 }}
			                    </strong>

			                    <span>
			                        0–3 Months
			                    </span>

			                </div>

			            </div>


			            <div class="lease-expiry-summary-item">

			                <span class="lease-expiry-dot expiry-3-6"></span>

			                <div>

			                    <strong>
			                        {{ $leaseExpiry3To6 ?? 24 }}
			                    </strong>

			                    <span>
			                        3–6 Months
			                    </span>

			                </div>

			            </div>


			            <div class="lease-expiry-summary-item">

			                <span class="lease-expiry-dot expiry-6-12"></span>

			                <div>

			                    <strong>
			                        {{ $leaseExpiry6To12 ?? 32 }}
			                    </strong>

			                    <span>
			                        6–12 Months
			                    </span>

			                </div>

			            </div>


			            <div class="lease-expiry-summary-item">

			                <span class="lease-expiry-dot expiry-12-plus"></span>

			                <div>

			                    <strong>
			                        {{ $leaseExpiry12Plus ?? 68 }}
			                    </strong>

			                    <span>
			                        12+ Months
			                    </span>

			                </div>

			            </div>

			        </div>

			    </div>

			</div>



			{{-- =====================================================
			     RENTAL COLLECTION
			====================================================== --}}

			<div class="dashboard-grid-item">

			    <div class="dashboard-panel rental-collection-panel">

			        <div class="dashboard-panel-header">

			            <div>

			                <h5 class="dashboard-panel-title">
			                    Rental Collection
			                </h5>

			                <span class="dashboard-panel-subtitle">
			                    Current collection performance
			                </span>

			            </div>

			            <a
			                href=""
			                class="dashboard-view-all"
			            >
			                View All
			            </a>

			        </div>


			        <div class="rental-collection-content">


			            <div class="rental-collection-chart">

			                <canvas id="rentalCollectionChart"></canvas>


			                <div class="rental-collection-center">

			                    <strong>
			                        {{ $collectionRate ?? '96.4%' }}
			                    </strong>

			                    <span>
			                        Collection Rate
			                    </span>

			                </div>

			            </div>


			            <div class="rental-collection-summary">


			                <div class="rental-summary-item">

			                    <span class="rental-summary-label">
			                        Billed
			                    </span>

			                    <strong class="rental-summary-billed">
			                        {{ $rentalBilled ?? '$1.20M' }}
			                    </strong>

			                </div>


			                <div class="rental-summary-item">

			                    <span class="rental-summary-label">
			                        Collected
			                    </span>

			                    <strong class="rental-summary-collected">
			                        {{ $rentalCollected ?? '$1.16M' }}
			                    </strong>

			                </div>


			                <div class="rental-summary-item">

			                    <span class="rental-summary-label">
			                        Outstanding
			                    </span>

			                    <strong class="rental-summary-outstanding">
			                        {{ $rentalOutstanding ?? '$0.04M' }}
			                    </strong>

			                </div>


			            </div>

			        </div>

			    </div>

			</div>


			</div>

			{{-- =========================================================
			     FINANCIAL SUMMARY
			========================================================= --}}

			<div class="dashboard-panel financial-summary-panel">

			    <div class="dashboard-panel-header">

			        <div>

			            <h5 class="dashboard-panel-title">
			                Financial Summary
			            </h5>

			            <span class="dashboard-panel-subtitle">
			                Current financial performance
			            </span>

			        </div>


			        <a
			            href="{{ route('admin.revenue.dashboard') }}"
			            class="dashboard-view-all"
			        >
			            View Revenue
			        </a>

			    </div>


			    <div class="financial-summary-grid">


			        {{-- REVENUE --}}

			        <div class="financial-summary-card">

			            <div class="financial-summary-icon financial-icon-revenue">

			                <i class="ri-money-rupee-circle-line"></i>

			            </div>


			            <div class="financial-summary-content">

			                <span class="financial-summary-label">
			                    Revenue
			                </span>

			                <strong>
			                    {{ $financialRevenue ?? '$12.80M' }}
			                </strong>

			                <small class="financial-positive">

			                    <i class="ri-arrow-up-line"></i>

			                    {{ $financialRevenueChange ?? '5.2%' }}

			                    vs last period

			                </small>

			            </div>

			        </div>


			        {{-- OPERATING EXPENSE --}}

			        <div class="financial-summary-card">

			            <div class="financial-summary-icon financial-icon-expense">

			                <i class="ri-wallet-3-line"></i>

			            </div>


			            <div class="financial-summary-content">

			                <span class="financial-summary-label">
			                    Operating Expense
			                </span>

			                <strong>
			                    {{ $financialExpense ?? '$4.10M' }}
			                </strong>

			                <small class="financial-negative">

			                    <i class="ri-arrow-up-line"></i>

			                    {{ $financialExpenseChange ?? '2.8%' }}

			                    vs last period

			                </small>

			            </div>

			        </div>


			        {{-- NOI --}}

			        <div class="financial-summary-card">

			            <div class="financial-summary-icon financial-icon-noi">

			                <i class="ri-line-chart-line"></i>

			            </div>


			            <div class="financial-summary-content">

			                <span class="financial-summary-label">
			                    Net Operating Income
			                </span>

			                <strong>
			                    {{ $financialNoi ?? '$8.70M' }}
			                </strong>

			                <small class="financial-positive">

			                    <i class="ri-arrow-up-line"></i>

			                    {{ $financialNoiChange ?? '6.1%' }}

			                    vs last period

			                </small>

			            </div>

			        </div>


			        {{-- NET PROFIT --}}

			        <div class="financial-summary-card">

			            <div class="financial-summary-icon financial-icon-profit">

			                <i class="ri-funds-line"></i>

			            </div>


			            <div class="financial-summary-content">

			                <span class="financial-summary-label">
			                    Net Profit
			                </span>

			                <strong>
			                    {{ $financialProfit ?? '$7.40M' }}
			                </strong>

			                <small class="financial-positive">

			                    <i class="ri-arrow-up-line"></i>

			                    {{ $financialProfitChange ?? '7.4%' }}

			                    vs last period

			                </small>

			            </div>

			        </div>


			        {{-- NOI MARGIN --}}

			        <div class="financial-summary-card">

			            <div class="financial-summary-icon financial-icon-margin">

			                <i class="ri-percent-line"></i>

			            </div>


			            <div class="financial-summary-content">

			                <span class="financial-summary-label">
			                    NOI Margin
			                </span>

			                <strong>
			                    {{ $financialNoiMargin ?? '68.0%' }}
			                </strong>

			                <small class="financial-positive">

			                    <i class="ri-arrow-up-line"></i>

			                    {{ $financialMarginChange ?? '1.4%' }}

			                    vs last period

			                </small>

			            </div>

			        </div>


			    </div>

			</div>

			{{-- =========================================================
			     TENANT & OCCUPANCY SUMMARY
			========================================================= --}}

			<div class="dashboard-panel tenant-occupancy-panel">

			    <div class="dashboard-panel-header">

			        <div>

			            <h5 class="dashboard-panel-title">
			                Tenant & Occupancy Summary
			            </h5>

			            <span class="dashboard-panel-subtitle">
			                Current tenant and unit occupancy position
			            </span>

			        </div>

			        <a
			            href=""
			            class="dashboard-view-all"
			        >
			            View Tenants
			        </a>

			    </div>


			    <div class="tenant-occupancy-grid">


			        {{-- TOTAL TENANTS --}}

			        <a
			            href=""
			            class="tenant-occupancy-card"
			        >

			            <div class="tenant-occupancy-icon tenant-icon-blue">

			                <i class="ri-user-3-line"></i>

			            </div>

			            <div>

			                <span>
			                    Total Tenants
			                </span>

			                <strong>
			                    {{ $totalTenants ?? 128 }}
			                </strong>

			            </div>

			        </a>


			        {{-- ACTIVE TENANTS --}}

			        <a
			            href=""
			            class="tenant-occupancy-card"
			        >

			            <div class="tenant-occupancy-icon tenant-icon-green">

			                <i class="ri-user-follow-line"></i>

			            </div>

			            <div>

			                <span>
			                    Active Tenants
			                </span>

			                <strong>
			                    {{ $activeTenants ?? 116 }}
			                </strong>

			            </div>

			        </a>


			        {{-- OCCUPIED UNITS --}}

			        <a
			            href=""
			            class="tenant-occupancy-card"
			        >

			            <div class="tenant-occupancy-icon tenant-icon-purple">

			                <i class="ri-store-2-line"></i>

			            </div>

			            <div>

			                <span>
			                    Occupied Units
			                </span>

			                <strong>
			                    {{ $occupiedUnits ?? 116 }}
			                </strong>

			            </div>

			        </a>


			        {{-- VACANT UNITS --}}

			        <a
			            href=""
			            class="tenant-occupancy-card"
			        >

			            <div class="tenant-occupancy-icon tenant-icon-orange">

			                <i class="ri-store-line"></i>

			            </div>

			            <div>

			                <span>
			                    Vacant Units
			                </span>

			                <strong>
			                    {{ $vacantUnits ?? 12 }}
			                </strong>

			            </div>

			        </a>


			        {{-- OCCUPANCY RATE --}}

			        <div class="tenant-occupancy-card occupancy-rate-card">

			            <div class="tenant-occupancy-icon tenant-icon-cyan">

			                <i class="ri-pie-chart-line"></i>

			            </div>

			            <div>

			                <span>
			                    Occupancy Rate
			                </span>

			                <strong>
			                    {{ $dashboardOccupancyRate ?? '90.6%' }}
			                </strong>

			            </div>

			        </div>


			    </div>


			    {{-- OCCUPANCY PROGRESS --}}

			    <div class="occupancy-progress-section">

			        <div class="occupancy-progress-header">

			            <span>
			                Portfolio Occupancy
			            </span>

			            <strong>
			                {{ $dashboardOccupancyRate ?? '90.6%' }}
			            </strong>

			        </div>


			        <div class="occupancy-progress-track">

			            <div
			                class="occupancy-progress-bar"
			                style="width: {{ $dashboardOccupancyPercent ?? 90.6 }}%;"
			            ></div>

			        </div>

			    </div>

			</div>

			{{-- =========================================================
			     FIT-OUT & HANDOVER SUMMARY
			========================================================= --}}

			<div class="dashboard-panel fitout-summary-panel">

			    <div class="dashboard-panel-header">

			        <div>

			            <h5 class="dashboard-panel-title">
			                Fit-Out & Handover
			            </h5>

			            <span class="dashboard-panel-subtitle">
			                Current fit-out activity and handover status
			            </span>

			        </div>

			        <a
			            href="{{ route('admin.fitout.dashboard') }}"
			            class="dashboard-view-all"
			        >
			            View Dashboard
			        </a>

			    </div>


			    <div class="fitout-summary-grid">


			        {{-- REQUESTS --}}

			        <a
			            href="{{ route('admin.fitout.requests.index') }}"
			            class="fitout-summary-card"
			        >

			            <div class="fitout-summary-icon fitout-icon-blue">

			                <i class="ri-file-list-3-line"></i>

			            </div>

			            <div class="fitout-summary-content">

			                <span>
			                    Active Requests
			                </span>

			                <strong>
			                    {{ $fitoutRequests ?? 14 }}
			                </strong>

			            </div>

			        </a>


			        {{-- APPROVALS --}}

			        <a
			            href="{{ route('admin.fitout.approvals.index') }}"
			            class="fitout-summary-card"
			        >

			            <div class="fitout-summary-icon fitout-icon-orange">

			                <i class="ri-checkbox-circle-line"></i>

			            </div>

			            <div class="fitout-summary-content">

			                <span>
			                    Pending Approvals
			                </span>

			                <strong>
			                    {{ $fitoutApprovals ?? 5 }}
			                </strong>

			            </div>

			        </a>


			        {{-- CONTRACTORS --}}

			        <a
			            href="{{ route('admin.fitout.contractors.index') }}"
			            class="fitout-summary-card"
			        >

			            <div class="fitout-summary-icon fitout-icon-purple">

			                <i class="ri-hard-hat-line"></i>

			            </div>

			            <div class="fitout-summary-content">

			                <span>
			                    Active Contractors
			                </span>

			                <strong>
			                    {{ $fitoutContractors ?? 8 }}
			                </strong>

			            </div>

			        </a>


			        {{-- INSPECTIONS --}}

			        <a
			            href="{{ route('admin.fitout.inspections.index') }}"
			            class="fitout-summary-card"
			        >

			            <div class="fitout-summary-icon fitout-icon-cyan">

			                <i class="ri-search-eye-line"></i>

			            </div>

			            <div class="fitout-summary-content">

			                <span>
			                    Inspections
			                </span>

			                <strong>
			                    {{ $fitoutInspections ?? 7 }}
			                </strong>

			            </div>

			        </a>


			        {{-- SNAGS --}}

			        <a
			            href="{{ route('admin.fitout.snags.index') }}"
			            class="fitout-summary-card"
			        >

			            <div class="fitout-summary-icon fitout-icon-red">

			                <i class="ri-error-warning-line"></i>

			            </div>

			            <div class="fitout-summary-content">

			                <span>
			                    Open Snags
			                </span>

			                <strong>
			                    {{ $fitoutSnags ?? 21 }}
			                </strong>

			            </div>

			        </a>


			        {{-- HANDOVERS --}}

			        <a
			            href="{{ route('admin.fitout.handovers.index') }}"
			            class="fitout-summary-card"
			        >

			            <div class="fitout-summary-icon fitout-icon-green">

			                <i class="ri-key-2-line"></i>

			            </div>

			            <div class="fitout-summary-content">

			                <span>
			                    Pending Handovers
			                </span>

			                <strong>
			                    {{ $fitoutHandovers ?? 4 }}
			                </strong>

			            </div>

			        </a>


			    </div>

			</div>

			
			

			 
		</div>
	  </div>
	</div>
</section> 
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {


    const canvas = document.getElementById(
        'portfolioPerformanceChart'
    );


    if (!canvas) {
        return;
    }


    const ctx = canvas.getContext('2d');


    const labels = [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun'
    ];


    const revenue = [
        8.5,
        9.8,
        10.6,
        11.2,
        11.8,
        12.5
    ];


    const noi = [
        5.2,
        6.1,
        6.6,
        7.0,
        7.5,
        8.1
    ];


    const operatingExpense = [
        2.1,
        2.4,
        2.7,
        2.9,
        3.1,
        3.4
    ];


    const netProfit = [
        3.1,
        3.7,
        3.9,
        4.1,
        4.4,
        4.7
    ];


    new Chart(ctx, {

        type: 'line',

        data: {

            labels: labels,

            datasets: [

                {
                    label: 'Revenue',

                    data: revenue,

                    borderColor: '#2563eb',

                    backgroundColor: 'transparent',

                    borderWidth: 2,

                    tension: 0.35,

                    pointRadius: 3,

                    pointHoverRadius: 5
                },


                {
                    label: 'NOI',

                    data: noi,

                    borderColor: '#10b981',

                    backgroundColor: 'transparent',

                    borderWidth: 2,

                    tension: 0.35,

                    pointRadius: 3,

                    pointHoverRadius: 5
                },


                {
                    label: 'Operating Expense',

                    data: operatingExpense,

                    borderColor: '#f59e0b',

                    backgroundColor: 'transparent',

                    borderWidth: 2,

                    tension: 0.35,

                    pointRadius: 3,

                    pointHoverRadius: 5
                },


                {
                    label: 'Net Profit',

                    data: netProfit,

                    borderColor: '#8b5cf6',

                    backgroundColor: 'transparent',

                    borderWidth: 2,

                    tension: 0.35,

                    pointRadius: 3,

                    pointHoverRadius: 5
                }

            ]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,


            interaction: {

                mode: 'index',

                intersect: false

            },


            plugins: {

                legend: {
                    display: false
                },


                tooltip: {

                    backgroundColor: '#172b4d',

                    titleFont: {
                        size: 11
                    },

                    bodyFont: {
                        size: 10
                    },

                    padding: 10

                }

            },


            scales: {

                x: {

                    grid: {
                        display: false
                    },

                    ticks: {

                        color: '#98a2b3',

                        font: {
                            size: 9
                        }

                    }

                },


                y: {

                    beginAtZero: true,

                    grid: {

                        color: '#f0f2f5',

                        drawBorder: false

                    },

                    ticks: {

                        color: '#98a2b3',

                        font: {
                            size: 9
                        },

                        callback: function(value) {

                            return value + 'M';

                        }

                    }

                }

            }

        }

    });


});

</script>

<script>

document.addEventListener('DOMContentLoaded', function () {


    const canvas = document.getElementById(
        'portfolioAssetTypeChart'
    );


    if (!canvas) {
        return;
    }


    const ctx = canvas.getContext('2d');


    new Chart(ctx, {

        type: 'doughnut',

        data: {

            labels: [
                'Retail',
                'Residential',
                'Office',
                'Hospitality',
                'Industrial'
            ],

            datasets: [

                {

                    data: [
                        75.20,
                        45.60,
                        28.70,
                        18.30,
                        17.20
                    ],

                    backgroundColor: [
                        '#2563eb',
                        '#10b981',
                        '#f59e0b',
                        '#8b5cf6',
                        '#06b6d4'
                    ],

                    borderWidth: 2,

                    borderColor: '#ffffff',

                    hoverOffset: 5

                }

            ]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,


            cutout: '62%',


            plugins: {

                legend: {

                    display: false

                },


                tooltip: {

                    callbacks: {

                        label: function (context) {

                            return ' $'
                                + context.parsed
                                + 'M';

                        }

                    }

                }

            }

        }

    });


});

</script>

<script>

document.addEventListener('DOMContentLoaded', function () {


    const canvas = document.getElementById(
        'leaseExpiryChart'
    );


    if (!canvas) {
        return;
    }


    const ctx = canvas.getContext('2d');


    new Chart(ctx, {

        type: 'bar',

        data: {

            labels: [
                '0–3 Months',
                '3–6 Months',
                '6–12 Months',
                '12+ Months'
            ],

            datasets: [

                {
                    label: 'No. of Leases',

                    data: [
                        18,
                        24,
                        32,
                        68
                    ],

                    backgroundColor: '#2563eb',

                    borderRadius: 3,

                    barPercentage: 0.65,

                    categoryPercentage: 0.7,

                    yAxisID: 'leases'

                },

                {
                    label: 'Area (m²)',

                    data: [
                        8250,
                        11500,
                        16200,
                        34500
                    ],

                    backgroundColor: '#10b981',

                    borderRadius: 3,

                    barPercentage: 0.65,

                    categoryPercentage: 0.7,

                    yAxisID: 'area'

                }

            ]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,


            interaction: {

                mode: 'index',

                intersect: false

            },


            plugins: {

                legend: {

                    display: true,

                    position: 'top',

                    align: 'end',

                    labels: {

                        boxWidth: 7,

                        boxHeight: 7,

                        padding: 10,

                        font: {

                            size: 8

                        },

                        color: '#667085'

                    }

                },


                tooltip: {

                    backgroundColor: '#172b4d',

                    padding: 9,

                    titleFont: {

                        size: 10

                    },

                    bodyFont: {

                        size: 9

                    }

                }

            },


            scales: {

                x: {

                    grid: {

                        display: false

                    },

                    ticks: {

                        color: '#98a2b3',

                        font: {

                            size: 8

                        }

                    }

                },


                leases: {

                    beginAtZero: true,

                    position: 'left',

                    grid: {

                        color: '#f2f4f7'

                    },

                    ticks: {

                        color: '#98a2b3',

                        font: {

                            size: 8

                        }

                    },

                    title: {

                        display: true,

                        text: 'No. of Leases',

                        color: '#98a2b3',

                        font: {

                            size: 8

                        }

                    }

                },


                area: {

                    beginAtZero: true,

                    position: 'right',

                    grid: {

                        drawOnChartArea: false

                    },

                    ticks: {

                        color: '#98a2b3',

                        font: {

                            size: 8

                        },

                        callback: function(value) {

                            return value >= 1000
                                ? (value / 1000) + 'K'
                                : value;

                        }

                    },

                    title: {

                        display: true,

                        text: 'Area (m²)',

                        color: '#98a2b3',

                        font: {

                            size: 8

                        }

                    }

                }

            }

        }

    });


});

</script>

<script>

document.addEventListener('DOMContentLoaded', function () {


    const canvas = document.getElementById(
        'rentalCollectionChart'
    );


    if (!canvas) {
        return;
    }


    const ctx = canvas.getContext('2d');


    new Chart(ctx, {

        type: 'doughnut',

        data: {

            labels: [
                'Collected',
                'Outstanding'
            ],

            datasets: [

                {

                    data: [
                        1160000,
                        40000
                    ],

                    backgroundColor: [
                        '#10b981',
                        '#f59e0b'
                    ],

                    borderColor: '#ffffff',

                    borderWidth: 2,

                    hoverOffset: 5

                }

            ]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,

            cutout: '68%',


            plugins: {

                legend: {
                    display: false
                },


                tooltip: {

                    callbacks: {

                        label: function (context) {

                            const value =
                                context.parsed;

                            return ' $'
                                + (
                                    value / 1000000
                                ).toFixed(2)
                                + 'M';

                        }

                    }

                }

            }

        }

    });


});

</script>