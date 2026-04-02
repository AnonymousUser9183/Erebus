@extends('layouts.auth')

@section('title', 'Therapy & Counseling Guide - Finding Professional Help')

@section('breadcrumb', 'Therapy & Counseling')

@section('content')
<style>
    .therapy-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0;
    }

    .therapy-header {
        background: linear-gradient(135deg, #0d5b7c 0%, #1a7a99 100%);
        color: white;
        padding: 32px 24px;
        border-bottom: 3px solid #2a9db8;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px 8px 0 0;
    }

    .therapy-header h1 {
        font-size: 32px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .therapy-header p {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }

    .therapy-section {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 32px;
        margin-bottom: 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
    }

    .therapy-section h2 {
        color: #0d5b7c;
        font-size: 24px;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 2px solid #2a9db8;
    }

    .therapy-section h3 {
        color: #1a7a99;
        font-size: 18px;
        margin-top: 16px;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .therapy-section p {
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

    .therapy-section ul,
    .therapy-section ol {
        margin-left: 16px;
        margin-bottom: 16px;
    }

    .therapy-section li {
        margin-bottom: 12px;
        color: #666666;
        line-height: 1.7;
    }

    .therapy-types-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .therapy-card {
        background-color: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 20px;
    }

    .therapy-card h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .therapy-card p {
        margin: 0 0 12px 0;
        font-size: 14px;
        color: #666666;
        line-height: 1.6;
    }

    .therapy-card ul {
        margin-left: 0;
        padding-left: 20px;
        margin-bottom: 0;
    }

    .therapy-card li {
        margin-bottom: 6px;
        font-size: 13px;
    }

    .provider-types {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .provider-card {
        background-color: #f0f9fb;
        border: 2px solid #2a9db8;
        border-radius: 6px;
        padding: 16px;
    }

    .provider-card h4 {
        color: #0d5b7c;
        font-size: 15px;
        margin: 0 0 10px 0;
        font-weight: 600;
    }

    .provider-card p {
        margin: 0;
        font-size: 13px;
        color: #666666;
        line-height: 1.5;
    }

    .finding-steps {
        background-color: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .step-item {
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    .step-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .step-item strong {
        color: #0d5b7c;
        font-size: 14px;
    }

    .step-item p {
        margin: 6px 0 0 0;
        font-size: 13px;
        color: #666666;
        line-height: 1.6;
    }

    .questions-box {
        background-color: #dcfce7;
        border: 2px solid #22c55e;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .questions-box h4 {
        color: #22c55e;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .questions-box ol {
        margin: 0;
    }

    .red-flags {
        background-color: #fee2e2;
        border: 2px solid #dc2626;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .red-flags h4 {
        color: #dc2626;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .red-flags ul {
        margin: 0;
    }

    .disclaimer-section {
        border: 2px solid #dc2626;
        background-color: #fee2e2;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .therapy-header h1 {
            font-size: 24px;
        }

        .therapy-header p {
            font-size: 14px;
        }

        .therapy-section {
            padding: 16px;
        }

        .therapy-section h2 {
            font-size: 20px;
        }

        .therapy-types-grid {
            grid-template-columns: 1fr;
        }

        .provider-types {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .therapy-header h1 {
            font-size: 20px;
        }

        .therapy-section {
            padding: 12px;
        }

        .therapy-section h2 {
            font-size: 18px;
        }

        .therapy-section h3 {
            font-size: 16px;
        }

        .info-box {
            padding: 12px;
        }
    }
</style>

<div class="therapy-page">
    <!-- Header -->
    <div class="therapy-header">
        <h1>🛋️ Therapy & Counseling Guide</h1>
        <p>Finding professional help. Understanding therapy options. Connecting with providers who can help.</p>
    </div>

    <!-- Why Therapy Matters -->
    <section class="therapy-section">
        <h2>Why Therapy Works</h2>
        <p>Professional therapy provides evidence-based treatment for mental health and substance use issues. Therapists are trained to help you understand yourself, develop coping skills, and create positive change in your life.</p>
        
        <div class="info-box success">
            <strong>✓ Therapy Is Effective</strong>
            <p>Research shows that therapy significantly improves outcomes for depression, anxiety, substance use disorders, trauma, and other mental health conditions. Combined with medication (when appropriate), therapy achieves success rates exceeding 70%.</p>
        </div>

        <h3>What Therapy Can Help With:</h3>
        <ul>
            <li>Substance use disorder and addiction</li>
            <li>Depression and mood disorders</li>
            <li>Anxiety and panic disorders</li>
            <li>Trauma and PTSD</li>
            <li>Relationship and family issues</li>
            <li>Eating disorders</li>
            <li>Stress management</li>
            <li>Life transitions and major decisions</li>
            <li>Self-esteem and identity issues</li>
            <li>Coping with grief and loss</li>
        </ul>
    </section>

    <!-- Types of Therapy -->
    <section class="therapy-section">
        <h2>Types of Therapy & Approaches</h2>
        <p>Different therapeutic approaches work for different people. Many therapists are trained in multiple approaches and can tailor treatment to your needs.</p>
        
        <div class="therapy-types-grid">
            <div class="therapy-card">
                <h4>Cognitive-Behavioral Therapy (CBT)</h4>
                <p>Focuses on connection between thoughts, feelings, and behaviors. Teaches practical coping skills to change negative thought patterns.</p>
                <ul>
                    <li>Very structured</li>
                    <li>Homework assignments</li>
                    <li>Highly researched</li>
                    <li>Fast results often</li>
                </ul>
            </div>

            <div class="therapy-card">
                <h4>Motivational Interviewing (MI)</h4>
                <p>Helps increase internal motivation for change. Excellent for substance use and ambivalence about change.</p>
                <ul>
                    <li>Collaborative</li>
                    <li>Non-judgmental</li>
                    <li>Resolves ambivalence</li>
                    <li>Increases commitment to change</li>
                </ul>
            </div>

            <div class="therapy-card">
                <h4>Dialectical Behavior Therapy (DBT)</h4>
                <p>Combines CBT with mindfulness. Particularly effective for emotional dysregulation, self-harm, and suicidal ideation.</p>
                <ul>
                    <li>Skills training</li>
                    <li>Individual therapy</li>
                    <li>Phone coaching</li>
                    <li>Structured program</li>
                </ul>
            </div>

            <div class="therapy-card">
                <h4>Trauma-Informed Therapy</h4>
                <p>Recognizes impact of trauma. Uses evidence-based trauma processing like EMDR or CPT.</p>
                <ul>
                    <li>Addresses trauma roots</li>
                    <li>Safety-focused</li>
                    <li>Restores sense of control</li>
                    <li>Healing-oriented</li>
                </ul>
            </div>

            <div class="therapy-card">
                <h4>Psychodynamic Therapy</h4>
                <p>Explores unconscious patterns and past experiences. Helps understand deeper motivations and recurring patterns.</p>
                <ul>
                    <li>Deeper exploration</li>
                    <li>Long-term focused</li>
                    <li>Historical perspective</li>
                    <li>Self-awareness building</li>
                </ul>
            </div>

            <div class="therapy-card">
                <h4>Family/Couples Therapy</h4>
                <p>Involves family members or partners. Addresses relationship dynamics and communication patterns.</p>
                <ul>
                    <li>Relationship focused</li>
                    <li>Communication skills</li>
                    <li>Family healing</li>
                    <li>Systemic approach</li>
                </ul>
            </div>
        </div>

        <div class="info-box">
            <strong>💡 Find What Works for You</strong>
            <p>Different approaches work for different people. Many therapists use integrative approaches combining multiple methods. Don't hesitate to try different therapists to find the right fit.</p>
        </div>
    </section>

    <!-- Types of Providers -->
    <section class="therapy-section">
        <h2>Types of Mental Health Providers</h2>
        
        <div class="provider-types">
            <div class="provider-card">
                <h4>Licensed Therapist/Counselor (LPC, LPCC)</h4>
                <p>Master's degree, 2-4 years training. Provide individual and group counseling for various issues.</p>
            </div>

            <div class="provider-card">
                <h4>Licensed Clinical Social Worker (LCSW)</h4>
                <p>Master's degree in social work. Often knowledgeable about community resources and systems.</p>
            </div>

            <div class="provider-card">
                <h4>Psychologist (PhD or PsyD)</h4>
                <p>Doctoral degree, extensive training. Can diagnose, test, and treat. Some can prescribe medication.</p>
            </div>

            <div class="provider-card">
                <h4>Psychiatrist (MD/DO)</h4>
                <p>Medical doctor specializing in mental health. Can prescribe medications. Limited talk therapy.</p>
            </div>

            <div class="provider-card">
                <h4>Psychiatric Nurse Practitioner (NP)</h4>
                <p>Advanced nursing degree. Can prescribe medications and provide therapy in many states.</p>
            </div>

            <div class="provider-card">
                <h4>Addiction Specialist/Counselor</h4>
                <p>Specialized training in substance use disorders. May have lived experience in recovery.</p>
            </div>
        </div>

        <div class="info-box warning">
            <strong>⚠️ Check Credentials</strong>
            <p>Verify licenses and credentials. Each state has different licensing requirements. Make sure your provider is licensed and in good standing. You can check licenses at your state's licensing board.</p>
        </div>
    </section>

    <!-- Finding a Provider -->
    <section class="therapy-section">
        <h2>How to Find a Therapist</h2>
        
        <div class="finding-steps">
            <h3 style="margin-top: 0; color: #0d5b7c;">Step-by-Step Process</h3>
            
            <div class="step-item">
                <strong>1. Identify Your Needs</strong>
                <p>What are you seeking help for? (substance use, depression, anxiety, trauma, etc.) Do you prefer in-person or virtual? What's your budget?</p>
            </div>
            
            <div class="step-item">
                <strong>2. Check Your Insurance</strong>
                <p>Call your insurance or check their website for in-network providers. Ask about mental health coverage, copays, and deductibles.</p>
            </div>
            
            <div class="step-item">
                <strong>3. Search for Providers</strong>
                <p>Use Psychology Today directory, TherapyDen, GoodTherapy, or your insurance's provider list. Filter by specialty, location, and insurance.</p>
            </div>
            
            <div class="step-item">
                <strong>4. Read Profiles & Reviews</strong>
                <p>Check qualifications, specialties, experience, and reviews. Look for therapists with experience in your specific issues.</p>
            </div>
            
            <div class="step-item">
                <strong>5. Schedule Consultations</strong>
                <p>Many offer free brief phone consultations. Use this to assess fit and get questions answered. Don't commit to first therapist if you don't feel right.</p>
            </div>
            
            <div class="step-item">
                <strong>6. Have First Session</strong>
                <p>Go prepared with your history and questions. Therapists will assess your needs. Continue if it feels like a good fit.</p>
            </div>
        </div>

        <h3>Where to Look:</h3>
        <ul>
            <li><strong>Psychology Today:</strong> psychologytoday.com - Largest therapist directory</li>
            <li><strong>TherapyDen:</strong> therapyden.com - Specialty-focused matching</li>
            <li><strong>GoodTherapy:</strong> goodtherapy.org - Vetted provider directory</li>
            <li><strong>Your Insurance Website:</strong> Most have provider search tools</li>
            <li><strong>SAMHSA:</strong> 1-800-662-4357 - Can refer to local providers</li>
            <li><strong>Community Health Centers:</strong> Often offer sliding-scale therapy</li>
            <li><strong>University Clinics:</strong> Graduate students supervised by licensed therapists, reduced cost</li>
        </ul>
    </section>

    <!-- What to Ask -->
    <section class="therapy-section">
        <h2>Questions to Ask Potential Therapists</h2>
        
        <div class="questions-box">
            <h4>Important Questions During Consultation:</h4>
            <ol>
                <li>What licenses and credentials do you have?</li>
                <li>What is your experience with [your specific issue]?</li>
                <li>What therapeutic approaches do you use?</li>
                <li>What is your fee and do you accept my insurance?</li>
                <li>What is your availability? How long are sessions?</li>
                <li>Do you offer virtual sessions?</li>
                <li>How often would sessions typically be?</li>
                <li>What happens if we're not a good fit?</li>
                <li>Do you consult with other providers if needed?</li>
                <li>How do you handle crisis situations?</li>
            </ol>
        </div>

        <div class="info-box success">
            <strong>✓ Trust Your Gut</strong>
            <p>The therapeutic relationship is crucial. If something feels off, your instinct is right. It's okay to try different therapists until you find the right fit. Good fit = better outcomes.</p>
        </div>
    </section>

    <!-- Red Flags -->
    <section class="therapy-section">
        <h2>Red Flags: When to Switch Therapists</h2>
        
        <div class="red-flags">
            <h4>Watch Out For:</h4>
            <ul>
                <li>Therapist shows up late or seems disorganized</li>
                <li>Therapist shares excessive personal information about themselves</li>
                <li>You feel judged or criticized rather than supported</li>
                <li>No clear treatment plan or goals discussed</li>
                <li>Therapist pressures you to do specific things</li>
                <li>No progress after 8-10 sessions without explanation</li>
                <li>Sexual, romantic, or financial boundary violations</li>
                <li>Therapist diagnoses you without full assessment</li>
                <li>You don't feel heard or understood</li>
                <li>Therapist is dismissive of your concerns</li>
            </ul>
        </div>

        <div class="info-box danger">
            <strong>🚨 Boundary Violations</strong>
            <p>Therapists must maintain professional boundaries. Any sexual, romantic, or inappropriate personal relationships are serious violations. If this happens, report it to your state's licensing board immediately.</p>
        </div>
    </section>

    <!-- Cost & Access -->
    <section class="therapy-section">
        <h2>Cost, Insurance & Access</h2>
        
        <h3>Payment Options:</h3>
        <ul>
            <li><strong>Insurance:</strong> Many plans cover therapy. Check your benefits</li>
            <li><strong>Employee Assistance Program (EAP):</strong> Free therapy through employer (often 3-6 sessions)</li>
            <li><strong>Medicaid:</strong> Covers therapy for eligible individuals in most states</li>
            <li><strong>Community Health Centers:</strong> Sliding scale based on income</li>
            <li><strong>Teaching Clinics:</strong> Graduate students supervised by licensed therapists, reduced cost</li>
            <li><strong>Online Platforms:</strong> Some affordable options like BetterHelp, Talkspace (check quality)</li>
            <li><strong>Free/Low-Cost:</strong> Some nonprofits offer free counseling</li>
        </ul>

        <h3>Accessing Virtual Therapy:</h3>
        <ul>
            <li>Many therapists now offer online sessions via Zoom or secure platforms</li>
            <li>Great for accessibility, convenience, and privacy</li>
            <li>Same quality of care as in-person therapy</li>
            <li>Often easier to find specialists through virtual options</li>
            <li>Ensure HIPAA-compliant platform is used</li>
        </ul>

        <div class="info-box success">
            <strong>✓ Therapy Is Worth It</strong>
            <p>Cost shouldn't prevent you from getting help. There are always sliding-scale, community, or online options. Investing in mental health is investing in your future.</p>
        </div>
    </section>

    <!-- Getting Started -->
    <section class="therapy-section">
        <h2>Getting Started Today</h2>
        
        <h3>You Can Start Right Now By:</h3>
        <ul>
            <li>Calling your insurance to ask for provider recommendations</li>
            <li>Visiting Psychology Today and filtering by specialty and insurance</li>
            <li>Calling SAMHSA (1-800-662-4357) for referrals to local therapists</li>
            <li>Googling "[your city] sliding scale therapy" for affordable options</li>
            <li>Asking your doctor for mental health provider recommendations</li>
            <li>Contacting community health centers directly</li>
            <li>Looking into online therapy platforms for quick access</li>
        </ul>

        <div class="info-box">
            <strong>💡 Your Mental Health Matters</strong>
            <p>Taking the step to seek therapy is brave and important. You deserve professional support. Don't wait for things to get worse. Start looking for a therapist today.</p>
        </div>
    </section>

    <!-- Disclaimer -->
    <section class="therapy-section disclaimer-section">
        <h2>⚠️ Important Information</h2>
        <p>This information is educational and not a substitute for professional mental health care. Always consult with qualified mental health professionals for diagnosis and treatment.</p>
        <ul>
            <li>Therapy is most effective when you actively participate</li>
            <li>Finding the right therapist takes time - don't give up</li>
            <li>Crisis situations require emergency services (call 911)</li>
            <li>Verify provider licenses and credentials</li>
            <li>Mental health treatment is a process - progress takes time</li>
        </ul>
        <p><strong>You deserve professional support. Help is available. Start your therapy journey today.</strong></p>
    </section>
</div>
@endsection
