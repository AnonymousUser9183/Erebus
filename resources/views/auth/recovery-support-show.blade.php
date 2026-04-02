@extends('layouts.auth')

@section('title', 'Recovery Support Resources - Treatment and Help')

@section('breadcrumb', 'Recovery Support')

@section('content')
<style>
    .recovery-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0;
    }

    .recovery-header {
        background: linear-gradient(135deg, #0d5b7c 0%, #1a7a99 100%);
        color: white;
        padding: 32px 24px;
        border-bottom: 3px solid #2a9db8;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px 8px 0 0;
    }

    .recovery-header h1 {
        font-size: 32px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .recovery-header p {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }

    .recovery-section {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 32px;
        margin-bottom: 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
    }

    .recovery-section h2 {
        color: #0d5b7c;
        font-size: 24px;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 2px solid #2a9db8;
    }

    .recovery-section h3 {
        color: #1a7a99;
        font-size: 18px;
        margin-top: 16px;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .recovery-section p {
        color: #666666;
        line-height: 1.8;
        margin-bottom: 16px;
        font-size: 15px;
    }

    .info-box {
        background-color: #f0f9fb;
        border-left: 4px solid #2a9db8;
        padding: 16px;
        border-radius: 6px;
        margin-bottom: 16px;
    }

    .info-box.warning {
        background-color: #fef3c7;
        border-left-color: #f59e0b;
    }

    .info-box.danger {
        background-color: #fee2e2;
        border-left-color: #dc2626;
    }

    .info-box.success {
        background-color: #dcfce7;
        border-left-color: #22c55e;
    }

    .info-box strong {
        color: #1a1a1a;
        display: block;
        margin-bottom: 8px;
        font-size: 16px;
    }

    .info-box p {
        margin: 0;
        font-size: 14px;
        color: #333333;
    }

    .recovery-section ul,
    .recovery-section ol {
        margin-left: 16px;
        margin-bottom: 16px;
    }

    .recovery-section li {
        margin-bottom: 12px;
        color: #666666;
        line-height: 1.7;
    }

    .treatment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .treatment-card {
        background-color: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 20px;
    }

    .treatment-card h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .treatment-card p {
        margin: 0 0 12px 0;
        font-size: 14px;
        color: #666666;
        line-height: 1.6;
    }

    .treatment-card ul {
        margin-left: 0;
        padding-left: 20px;
        margin-bottom: 0;
    }

    .treatment-card li {
        margin-bottom: 6px;
        font-size: 13px;
    }

    .support-groups {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .group-card {
        background-color: #f0f9fb;
        border: 2px solid #2a9db8;
        border-radius: 6px;
        padding: 16px;
    }

    .group-card h4 {
        color: #0d5b7c;
        font-size: 15px;
        margin: 0 0 10px 0;
        font-weight: 600;
    }

    .group-card p {
        margin: 0;
        font-size: 13px;
        color: #666666;
        line-height: 1.5;
    }

    .resources-list {
        background-color: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .resources-list h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin: 0 0 16px 0;
        font-weight: 600;
    }

    .resource-item {
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    .resource-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .resource-item strong {
        color: #0d5b7c;
        font-size: 14px;
    }

    .resource-item p {
        margin: 4px 0 0 0;
        font-size: 13px;
        color: #666666;
        line-height: 1.5;
    }

    .emergency-box {
        background-color: #fee2e2;
        border: 3px solid #dc2626;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 16px;
        text-align: center;
    }

    .emergency-box h3 {
        color: #dc2626;
        font-size: 20px;
        margin: 0 0 12px 0;
    }

    .emergency-box .number {
        font-size: 28px;
        color: #dc2626;
        font-weight: bold;
        margin: 12px 0;
        font-family: monospace;
    }

    .recovery-timeline {
        background-color: #f0f9fb;
        border-left: 4px solid #2a9db8;
        padding: 20px;
        border-radius: 6px;
        margin-bottom: 16px;
    }

    .timeline-item {
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    .timeline-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .timeline-item strong {
        color: #0d5b7c;
        font-size: 14px;
    }

    .timeline-item p {
        margin: 6px 0 0 0;
        font-size: 13px;
        color: #666666;
        line-height: 1.6;
    }

    .disclaimer-section {
        border: 2px solid #dc2626;
        background-color: #fee2e2;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .recovery-header h1 {
            font-size: 24px;
        }

        .recovery-header p {
            font-size: 14px;
        }

        .recovery-section {
            padding: 16px;
        }

        .recovery-section h2 {
            font-size: 20px;
        }

        .treatment-grid {
            grid-template-columns: 1fr;
        }

        .support-groups {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .recovery-header h1 {
            font-size: 20px;
        }

        .recovery-section {
            padding: 12px;
        }

        .recovery-section h2 {
            font-size: 18px;
        }

        .recovery-section h3 {
            font-size: 16px;
        }

        .info-box {
            padding: 12px;
        }

        .emergency-box .number {
            font-size: 24px;
        }
    }
</style>

<div class="recovery-page">
    <!-- Header -->
    <div class="recovery-header">
        <h1>🌱 Recovery Support Resources</h1>
        <p>Comprehensive information about treatment options, support services, and resources for recovery from substance use.</p>
    </div>

    <!-- Message of Hope -->
    <section class="recovery-section">
        <h2>Recovery Is Possible</h2>
        <p>Millions of people have recovered from substance use disorders. Recovery is real, it happens every day, and it works. You are not alone, and help is available right now.</p>
        
        <div class="info-box success">
            <strong>✓ You Deserve Support</strong>
            <p>Recovery is a journey, not a destination. There is no shame in asking for help. Treatment works. Medication-assisted treatment combined with counseling and community support has proven success rates exceeding 70% long-term recovery.</p>
        </div>

        <h3>Why Recovery Matters</h3>
        <ul>
            <li>Reclaim your health and stability</li>
            <li>Rebuild relationships and trust</li>
            <li>Recover financially and professionally</li>
            <li>Reduce overdose and health risks dramatically</li>
            <li>Find meaning and purpose in life</li>
            <li>Become the person you want to be</li>
        </ul>
    </section>

    <!-- Emergency Hotlines -->
    <section class="recovery-section">
        <h2>Crisis Support - Help Right Now</h2>
        
        <div class="emergency-box">
            <h3>National Crisis Numbers</h3>
            <p><strong>If you're in immediate danger or thinking of harming yourself:</strong></p>
            <div class="number">988</div>
            <p><strong>Suicide & Crisis Lifeline</strong> - Free, confidential, 24/7<br/>Call or text 988 (US only)</p>
        </div>

        <div class="recovery-section" style="margin-bottom: 16px; background-color: #dcfce7; border: 2px solid #22c55e; padding: 20px;">
            <h4 style="margin: 0 0 12px 0; color: #22c55e;">For Substance Use Crisis</h4>
            <p style="margin: 0; font-size: 16px;"><strong>1-800-662-4357</strong></p>
            <p style="margin: 8px 0 0 0; font-size: 14px;">SAMHSA National Helpline - Free, confidential, 24/7<br/>Treatment referral and support</p>
        </div>

        <div class="info-box danger">
            <strong>🚨 Overdose Emergency</strong>
            <p>Call 911 immediately if someone is overdosing. Many areas have Good Samaritan laws protecting you from legal consequences for seeking help. Your quick action can save a life.</p>
        </div>
    </section>

    <!-- Treatment Types -->
    <section class="recovery-section">
        <h2>Treatment Options</h2>
        <p>Recovery looks different for everyone. Multiple evidence-based treatment approaches exist:</p>
        
        <div class="treatment-grid">
            <div class="treatment-card">
                <h4>Medication-Assisted Treatment (MAT)</h4>
                <p>Medications combined with counseling and behavioral therapy. Highly effective for opioid and stimulant use disorders.</p>
                <ul>
                    <li><strong>Buprenorphine:</strong> Partial opioid agonist, less abuse potential</li>
                    <li><strong>Methadone:</strong> Full opioid agonist for severe dependence</li>
                    <li><strong>Naltrexone:</strong> Blocks opioid effects</li>
                    <li><strong>Antabuse:</strong> For alcohol use</li>
                </ul>
            </div>
            <div class="treatment-card">
                <h4>Behavioral Therapy</h4>
                <p>Talk therapy addressing thoughts, behaviors, and coping skills related to substance use.</p>
                <ul>
                    <li>Cognitive-Behavioral Therapy (CBT)</li>
                    <li>Motivational Interviewing (MI)</li>
                    <li>Dialectical Behavior Therapy (DBT)</li>
                    <li>Family therapy</li>
                </ul>
            </div>
            <div class="treatment-card">
                <h4>Residential Treatment</h4>
                <p>Intensive inpatient programs for people needing 24/7 support and supervision.</p>
                <ul>
                    <li>30-day programs</li>
                    <li>60-90 day programs</li>
                    <li>Long-term residential (6+ months)</li>
                    <li>Therapeutic communities</li>
                </ul>
            </div>
            <div class="treatment-card">
                <h4>Outpatient Programs</h4>
                <p>Treatment while living at home, attending appointments multiple times weekly.</p>
                <ul>
                    <li>Intensive outpatient (IOP)</li>
                    <li>Day treatment programs</li>
                    <li>Standard outpatient</li>
                    <li>Teletherapy/virtual sessions</li>
                </ul>
            </div>
            <div class="treatment-card">
                <h4>Peer Support</h4>
                <p>Community support and accountability from people with similar experiences in recovery.</p>
                <ul>
                    <li>Narcotics Anonymous (NA)</li>
                    <li>SMART Recovery</li>
                    <li>Cocaine Anonymous</li>
                    <li>Support groups</li>
                </ul>
            </div>
            <div class="treatment-card">
                <h4>Integrated Mental Health</h4>
                <p>Simultaneous treatment for substance use AND mental health conditions.</p>
                <ul>
                    <li>Depression treatment</li>
                    <li>Anxiety management</li>
                    <li>Trauma-informed care</li>
                    <li>Dual diagnosis treatment</li>
                </ul>
            </div>
        </div>

        <div class="info-box success">
            <strong>✓ Combined Approach Works Best</strong>
            <p>Research shows that combining medication (when appropriate), therapy, and peer support produces the best outcomes. Recovery is most successful when addressing all aspects of life.</p>
        </div>
    </section>

    <!-- Peer Support Groups -->
    <section class="recovery-section">
        <h2>Peer Support Communities</h2>
        <p>Many people find strength and hope in community support. Free peer-led support groups meet regularly:</p>
        
        <div class="support-groups">
            <div class="group-card">
                <h4>Narcotics Anonymous (NA)</h4>
                <p>12-step program for people recovering from drug addiction. Meetings available worldwide. Free to attend.</p>
            </div>
            <div class="group-card">
                <h4>SMART Recovery</h4>
                <p>Science-based alternative emphasizing self-empowerment and motivation over higher power.</p>
            </div>
            <div class="group-card">
                <h4>Cocaine Anonymous</h4>
                <p>12-step program specifically for cocaine and stimulant addiction recovery.</p>
            </div>
            <div class="group-card">
                <h4>LifeRing</h4>
                <p>Secular community for people in recovery from substance use. Peer-led support groups.</p>
            </div>
            <div class="group-card">
                <h4>Recovery Dharma</h4>
                <p>Combines Buddhist teachings with recovery principles. Emphasizes compassion and community.</p>
            </div>
            <div class="group-card">
                <h4>Online Support</h4>
                <p>Virtual support meetings available 24/7 through platforms like Zoom for accessibility.</p>
            </div>
        </div>

        <div class="info-box">
            <strong>💡 Finding Your Community</strong>
            <p>Attend several different meetings to find what resonates with you. Every community has its own culture and feel. Finding your people is key to long-term recovery.</p>
        </div>
    </section>

    <!-- Recovery Journey Timeline -->
    <section class="recovery-section">
        <h2>The Recovery Journey: What to Expect</h2>
        
        <div class="recovery-timeline">
            <div class="timeline-item">
                <strong>First Days (Acute Phase)</strong>
                <p>Withdrawal symptoms common but manageable. Medical support helps tremendously. Focus on getting through each day. Withdrawal is temporary; recovery is permanent.</p>
            </div>
            <div class="timeline-item">
                <strong>First Week (Early Recovery)</strong>
                <p>Physical symptoms typically peak then improve. Start building structure and routine. Connect with support network. Feel emotions more clearly as fog lifts.</p>
            </div>
            <div class="timeline-item">
                <strong>First Month</strong>
                <p>Energy increases. Sleep improves. Begin emotional healing. Attend treatment/support groups consistently. Navigate cravings with new coping tools. Relationships may begin healing.</p>
            </div>
            <div class="timeline-item">
                <strong>First 3-6 Months</strong>
                <p>Brain chemistry rebalancing. Mental clarity improves dramatically. Cravings typically decrease. Develop strong routine. Deepen support network. Celebrate milestones.</p>
            </div>
            <div class="timeline-item">
                <strong>6-12 Months</strong>
                <p>Stability increases. Confidence builds. Relationships strengthen. Work toward personal goals. Continue support involvement. Develop healthy coping skills.</p>
            </div>
            <div class="timeline-item">
                <strong>1+ Years (Long-Term Recovery)</strong>
                <p>Life reconstruction. Rebuild trust and relationships. Career/education progress. Recovery becomes integrated into identity. Help others in recovery.</p>
            </div>
        </div>

        <div class="info-box warning">
            <strong>⚠️ Relapse Is Not Failure</strong>
            <p>Relapse is common in recovery but does not erase progress. If relapse occurs, return to treatment immediately. Each attempt teaches and strengthens resolve. Most people who recover have tried multiple times.</p>
        </div>
    </section>

    <!-- Finding Treatment -->
    <section class="recovery-section">
        <h2>How to Find Treatment</h2>
        
        <div class="resources-list">
            <h4>Step-by-Step to Treatment</h4>
            
            <div class="resource-item">
                <strong>1. Call SAMHSA National Helpline</strong>
                <p><strong>1-800-662-4357</strong> - Free, confidential, 24/7<br/>They'll assess your situation and provide treatment options in your area, insurance coverage, and payment options.</p>
            </div>
            
            <div class="resource-item">
                <strong>2. Visit FindTreatment.gov</strong>
                <p>Online directory of treatment facilities. Search by location, type of service, and insurance acceptance. Reviews and ratings from patients available.</p>
            </div>
            
            <div class="resource-item">
                <strong>3. Talk to Your Doctor</strong>
                <p>Your primary care provider can refer to treatment programs and prescribe medication-assisted treatment. Many doctors can provide treatment directly.</p>
            </div>
            
            <div class="resource-item">
                <strong>4. Contact Local Programs</strong>
                <p>Harm reduction organizations, community health centers, and mental health clinics offer treatment referrals. Many have sliding scale fees.</p>
            </div>
            
            <div class="resource-item">
                <strong>5. Check Insurance Coverage</strong>
                <p>Most insurance plans cover substance use treatment. Call your insurance or check their website for in-network providers.</p>
            </div>
        </div>

        <h3>Cost Considerations:</h3>
        <ul>
            <li>Many treatment programs offer sliding scale fees based on income</li>
            <li>Some programs are completely free</li>
            <li>Insurance often covers substantial portions of treatment</li>
            <li>Medicaid covers treatment in most states</li>
            <li>Some employers have Employee Assistance Programs (EAP) covering treatment</li>
        </ul>
    </section>

    <!-- Resources Directory -->
    <section class="recovery-section">
        <h2>Resource Directory</h2>
        
        <h3>National Resources:</h3>
        <ul>
            <li><strong>SAMHSA National Helpline:</strong> 1-800-662-4357 - Treatment referral and support (24/7)</li>
            <li><strong>National Suicide Prevention Lifeline:</strong> 988 - Crisis support (24/7)</li>
            <li><strong>Crisis Text Line:</strong> Text HOME to 741741 - Mental health support</li>
            <li><strong>FindTreatment.gov:</strong> Online treatment directory</li>
            <li><strong>SMART Recovery:</strong> smartrecovery.org - Secular recovery support</li>
            <li><strong>NA.org:</strong> Narcotics Anonymous meeting finder</li>
        </ul>

        <h3>Medication Information:</h3>
        <ul>
            <li><strong>Buprenorphine Locator:</strong> findtreatment.gov for buprenorphine-waived doctors</li>
            <li><strong>Methadone Clinics:</strong> Contact SAMHSA for nearest clinic</li>
            <li><strong>Naltrexone Info:</strong> Ask your doctor about extended-release naltrexone (Vivitrol)</li>
        </ul>

        <h3>Support Organizations:</h3>
        <ul>
            <li><strong>Harm Reduction Coalition:</strong> harmreduction.org</li>
            <li><strong>Mental Health America:</strong> mhanational.org</li>
            <li><strong>Faces & Voices of Recovery:</strong> facesandvoicesofrecovery.org</li>
            <li><strong>Recovery Unplugged:</strong> recoveryunplugged.com</li>
        </ul>

        <div class="info-box success">
            <strong>✓ You're Not Starting From Nothing</strong>
            <p>Thousands of trained counselors, doctors, and peer supporters have dedicated their lives to helping people recover. You have access to that expertise and compassion. Reach out today.</p>
        </div>
    </section>

    <!-- Taking First Steps -->
    <section class="recovery-section">
        <h2>Taking the First Step</h2>
        
        <div class="info-box success">
            <strong>✓ Starting recovery today is possible</strong>
            <p>You don't need to have everything figured out. You don't need perfect motivation. You just need to be willing to try. One phone call can change everything.</p>
        </div>

        <h3>Right Now, You Can:</h3>
        <ul>
            <li>Call 1-800-662-4357 (SAMHSA) and talk to someone</li>
            <li>Text 988 if you need to talk about how you're feeling</li>
            <li>Find a NA meeting near you at NA.org</li>
            <li>Tell a trusted person you want help</li>
            <li>Schedule an appointment with your doctor</li>
            <li>Visit a local community health center</li>
            <li>Go to an open support group meeting</li>
        </ul>

        <h3>It's Okay To Feel:</h3>
        <ul>
            <li>Scared - Asking for help is brave</li>
            <li>Uncertain - You don't need to have all answers</li>
            <li>Embarrassed - Your past doesn't define your future</li>
            <li>Hopeless - That feeling will change with support and treatment</li>
            <li>Tired - You're about to get real rest and healing</li>
        </ul>

        <div class="info-box">
            <strong>💡 Recovery Is Real</strong>
            <p>Thousands of people have recovered from severe addiction. They were in your position. They felt hopeless. They got help. They recovered. You can too. Your story isn't over - it's just beginning.</p>
        </div>
    </section>

    <!-- Disclaimer -->
    <section class="recovery-section disclaimer-section">
        <h2>⚠️ Important Information</h2>
        <p>This information is provided for educational purposes to guide you toward professional help. It is not a substitute for professional medical, mental health, or legal advice.</p>
        <ul>
            <li>Always consult with qualified healthcare professionals for medical advice</li>
            <li>Treatment should be personalized to your specific situation</li>
            <li>Recovery is a process that takes time and ongoing support</li>
            <li>Professional help significantly increases success rates</li>
            <li>Relapse can occur and does not mean failure</li>
            <li>Your life has value and recovery is possible</li>
        </ul>
        <p><strong>You matter. Your life matters. Recovery is possible. Help is available. You are not alone. Call 988 or 1-800-662-4357 right now.</strong></p>
    </section>
</div>
@endsection
