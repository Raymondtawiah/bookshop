@extends('layouts.customer')

@section('content')
<div style="background: #1e1b4b; min-height: 100vh;">
    <!-- Hero Banner -->
    <div style="background: #312e81; padding: 100px 20px 80px; text-align: center; border-bottom: 4px solid #4f46e5;">
        <div style="display: inline-block; background: #dc2626; color: #ffffff; padding: 8px 20px; border-radius: 9999px; font-size: 14px; font-weight: 700; margin-bottom: 24px; letter-spacing: 0.05em;">
            Breaking Updates — January 2026
        </div>
        <h1 style="font-size: 52px; font-weight: 900; color: #ffffff; margin-bottom: 16px; line-height: 1.1;">
            🌍 U.S. Visa Policy Updates
        </h1>
        <p style="font-size: 20px; color: #e0e7ff; max-width: 650px; margin: 0 auto;">
            Important changes to U.S. visa issuance, social media vetting, and travel restrictions effective 2026.
        </p>
    </div>

    <!-- Content Cards -->
    <div style="background: #f8fafc; padding: 40px 0;">
        <div style="max-width: 900px; margin: 0 auto; padding: 0 16px; padding-top: 40px; display: flex; flex-direction: column; gap: 24px;">

            <!-- Card 1 -->
            <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #f1f5f9;">
                <div style="background: #dc2626; padding: 16px 24px; display: flex; align-items: center; justify-content: space-between;">
                    <h2 style="font-size: 18px; font-weight: 700; color: #ffffff; display: flex; align-items: center; gap: 8px;">
                        🇺🇸 Immigrant Visa Issuance Pauses &amp; Proclamations
                    </h2>
                    <span style="background: rgba(255,255,255,0.2); color: #ffffff; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 9999px;">Effective Jan 21, 2026</span>
                </div>
                <div style="padding: 24px; color: #1e293b; line-height: 1.7; display: flex; flex-direction: column; gap: 16px;">
                    <p>Effective January 21, 2026, the Department of State paused all immigrant visa issuances to nationals of countries, including <strong>Ghana</strong>, whose immigrants have a high rate of collecting public assistance at the expense of the U.S. taxpayer. Immigrant visa applicants who are nationals of impacted countries may submit visa applications and attend interviews, and the Department of State will continue to schedule consular appointments for visa interviews.</p>
                    <p>Pursuant to <strong>Presidential Proclamation 10998</strong> on Restricting and Limiting the Entry of Foreign Nationals to Protect the Security of the United States, which takes effect at 12:01 a.m. Eastern Standard Time on January 1, 2026, the United States is suspending or limiting entry and visa issuance to nationals of <strong>39 countries</strong> as well as individuals applying using travel documents issued or endorsed by the Palestinian Authority. Applicants who are subject to Presidential Proclamation 10998 may still submit visa applications and attend scheduled interviews, but they may be ineligible for visa issuance or admission to the United States.</p>
                    <p>Effective immediately, the Department of State has paused all visa issuances to <strong>diversity immigrant visa applicants</strong>.</p>
                    <p>For additional details, visit <a href="https://travel.state.gov" target="_blank" rel="noopener" style="color: #4f46e5; text-decoration: underline;">travel.state.gov</a>.</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #f1f5f9;">
                <div style="background: #d97706; padding: 16px 24px; display: flex; align-items: center; justify-content: space-between;">
                    <h2 style="font-size: 18px; font-weight: 700; color: #ffffff; display: flex; align-items: center; gap: 8px;">
                        📱 Mandatory Social Media Vetting
                    </h2>
                    <span style="background: rgba(255,255,255,0.2); color: #ffffff; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 9999px;">All Applicants</span>
                </div>
                <div style="padding: 24px; color: #1e293b; line-height: 1.7; display: flex; flex-direction: column; gap: 16px;">
                    <p>All individuals applying for an <strong>A-3, C-3 (if a domestic worker), G-5, H-1B, H-3, H-4 dependent of H-1B and H-3, F, M, J, K-1, K-2, K-3, Q, R-1, R-2, S, T, or U</strong> nonimmigrant visa are instructed to adjust the privacy setting on all social media accounts to <strong style="background: #fef3c7; padding: 2px 6px; border-radius: 4px;">&quot;public&quot;</strong> or <strong style="background: #fef3c7; padding: 2px 6px; border-radius: 4px;">&quot;open&quot;</strong> to facilitate vetting necessary to establish their identity and admissibility to the United States under U.S. law.</p>
                    <p>More information is available at <a href="https://travel.state.gov" target="_blank" rel="noopener" style="color: #4f46e5; text-decoration: underline;">travel.state.gov</a>.</p>
                </div>
            </div>

            <!-- Card 3 -->
            <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #f1f5f9;">
                <div style="background: #2563eb; padding: 16px 24px; display: flex; align-items: center; justify-content: space-between;">
                    <h2 style="font-size: 18px; font-weight: 700; color: #ffffff; display: flex; align-items: center; gap: 8px;">
                        🛂 Nonimmigrant Visa Appointment &amp; Fee Notice
                    </h2>
                    <span style="background: rgba(255,255,255,0.2); color: #ffffff; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 9999px;">Important</span>
                </div>
                <div style="padding: 24px; color: #1e293b; line-height: 1.7; display: flex; flex-direction: column; gap: 16px;">
                    <p>Applicants for U.S. nonimmigrant visas should schedule their visa interview appointments at the U.S. Embassy or Consulate in their country of residence or nationality.</p>
                    <p>Visa application fees are <strong>non-refundable</strong> and <strong>non-transferable</strong>. For more information, visit <a href="https://travel.state.gov" target="_blank" rel="noopener" style="color: #4f46e5; text-decoration: underline;">travel.state.gov</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
