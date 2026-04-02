@extends('layouts.auth')

@section('title', 'Legal Awareness & Rights - Know Your Rights in Substance Use Situations')

@section('breadcrumb', 'Legal Awareness')

@section('content')
<style>
    .legal-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0;
    }

    .legal-header {
        background: linear-gradient(135deg, #0d5b7c 0%, #1a7a99 100%);
        color: white;
        padding: 32px 24px;
        border-bottom: 3px solid #2a9db8;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px 8px 0 0;
    }

    .legal-header h1 {
        font-size: 32px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .legal-header p {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }

    .legal-section {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 32px;
        margin-bottom: 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
    }

    .legal-section h2 {
        color: #0d5b7c;
        font-size: 24px;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 2px solid #2a9db8;
    }

    .legal-section h3 {
        color: #1a7a99;
        font-size: 18px;
        margin-top: 16px;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .legal-section p {
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

    .legal-section ul,
    .legal-section ol {
        margin-left: 16px;
        margin-bottom: 16px;
    }

    .legal-section li {
        margin-bottom: 12px;
        color: #666666;
        line-height: 1.7;
    }

    .rights-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .rights-card {
        background-color: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 20px;
    }

    .rights-card h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .rights-card p {
        margin: 0 0 12px 0;
        font-size: 14px;
        color: #666666;
        line-height: 1.6;
    }

    .rights-card ul {
        margin-left: 0;
        padding-left: 20px;
        margin-bottom: 0;
    }

    .rights-card li {
        margin-bottom: 6px;
        font-size: 13px;
    }

    .scenario-box {
        background-color: #f0f9fb;
        border: 2px solid #2a9db8;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .scenario-box h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .scenario {
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    .scenario:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .scenario strong {
        color: #1a7a99;
        font-size: 14px;
    }

    .scenario p {
        margin: 6px 0 0 0;
        font-size: 13px;
        color: #666666;
        line-height: 1.6;
    }

    .checkpoints-box {
        background-color: #dcfce7;
        border: 2px solid #22c55e;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .checkpoints-box h4 {
        color: #22c55e;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .checkpoints-box ul {
        margin: 0;
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
        margin-bottom: 12px;
        padding-bottom: 12px;
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
    }

    .disclaimer-section {
        border: 2px solid #dc2626;
        background-color: #fee2e2;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .legal-header h1 {
            font-size: 24px;
        }

        .legal-header p {
            font-size: 14px;
        }

        .legal-section {
            padding: 16px;
        }

        .legal-section h2 {
            font-size: 20px;
        }

        .rights-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .legal-header h1 {
            font-size: 20px;
        }

        .legal-section {
            padding: 12px;
        }

        .legal-section h2 {
            font-size: 18px;
        }

        .legal-section h3 {
            font-size: 16px;
        }

        .info-box {
            padding: 12px;
        }
    }
</style>

<div class="legal-page">
    <!-- Header -->
    <div class="legal-header">
        <h1>⚖️ Legal Awareness & Rights</h1>
        <p>Know your constitutional rights. Understand substance-related laws. Protect yourself legally.</p>
    </div>

    <!-- Introduction -->
    <section class="legal-section">
        <h2>Your Constitutional Rights</h2>
        <p>Regardless of substance use, you have constitutional rights that apply in all situations with law enforcement. Understanding and exercising these rights protects you.</p>
        
        <div class="info-box success">
            <strong>✓ Rights Are Universal</strong>
            <p>Your constitutional rights apply to everyone, including people who use substances. These rights exist to protect you in your interactions with police and the legal system.</p>
        </div>

        <h3>Remember: Exercise Your Rights Calmly and Respectfully</h3>
        <p>Asserting your rights is legal and protected. Remain calm, respectful, and clear. Do not resist physically. Non-compliance with law enforcement can result in additional charges.</p>
    </section>

    <!-- Core Rights -->
    <section class="legal-section">
        <h2>Core Constitutional Rights You Have</h2>
        
        <div class="rights-grid">
            <div class="rights-card">
                <h4>🤐 Right to Remain Silent</h4>
                <p>You have the right to refuse to answer questions from police. This is called "remaining silent" or "invoking Miranda rights."</p>
                <ul>
                    <li>You don't have to talk to police</li>
                    <li>Say clearly: "I want to remain silent"</li>
                    <li>Don't answer questions</li>
                    <li>This applies even if arrested</li>
                </ul>
            </div>

            <div class="rights-card">
                <h4>⚖️ Right to an Attorney</h4>
                <p>You have the right to have a lawyer present during questioning. If arrested, you can request an attorney.</p>
                <ul>
                    <li>Ask for attorney clearly: "I want a lawyer"</li>
                    <li>Stop answering questions</li>
                    <li>Attorney present before questioning</li>
                    <li>Can't afford? Court provides public defender</li>
                </ul>
            </div>

            <div class="rights-card">
                <h4>🚔 Right to Refuse Searches</h4>
                <p>You can refuse to give permission for police to search you, your home, or your car (with exceptions).</p>
                <ul>
                    <li>Say: "I don't consent to a search"</li>
                    <li>Don't physically resist</li>
                    <li>Warrant exceptions apply</li>
                    <li>During traffic stop, police may search within reach</li>
                </ul>
            </div>

            <div class="rights-card">
                <h4>❓ Right to Know Why Arrested</h4>
                <p>Police must tell you why you're being arrested or detained. You can ask, and they must inform you.</p>
                <ul>
                    <li>Ask clearly: "Why am I being arrested?"</li>
                    <li>They must tell you the charges</li>
                    <li>This applies at arrest time</li>
                    <li>Know the specific charges</li>
                </ul>
            </div>

            <div class="rights-card">
                <h4>🔗 Right Against Self-Incrimination</h4>
                <p>You cannot be compelled to provide evidence against yourself. Remaining silent is protected.</p>
                <ul>
                    <li>Don't have to answer questions</li>
                    <li>Can't be punished for silence</li>
                    <li>Silent doesn't imply guilt</li>
                    <li>Attorney will advise you</li>
                </ul>
            </div>

            <div class="rights-card">
                <h4>📞 Right to Phone Call</h4>
                <p>Upon arrest, you have the right to make a phone call to contact attorney or family (not always immediate).</p>
                <ul>
                    <li>Ask to call attorney</li>
                    <li>Ask to call family/friend</li>
                    <li>Not always immediately</li>
                    <li>Usually within reasonable time</li>
                </ul>
            </div>
        </div>

        <div class="info-box warning">
            <strong>⚠️ Rights Must Be Exercised</strong>
            <p>Your constitutional rights only protect you if you exercise them. Simply having rights doesn't help if you don't use them. Say the words clearly: "I want to remain silent" and "I want a lawyer."</p>
        </div>
    </section>

    <!-- Interactions with Police -->
    <section class="legal-section">
        <h2>Police Interactions & Substance Use Laws</h2>
        
        <div class="scenario-box">
            <h4>Common Scenarios & Your Rights</h4>
            
            <div class="scenario">
                <strong>Stop & Frisk (Street)</strong>
                <p>Police can stop you and ask questions. You can refuse to answer. They can pat down outside of clothing if they suspect weapons. Stay calm and say clearly: "I don't consent to a search" and "I want a lawyer."</p>
            </div>
            
            <div class="scenario">
                <strong>Traffic Stop</strong>
                <p>Police can pull you over for traffic violations. License, registration, insurance must be provided. You don't have to consent to searches, though police may search within reach. Say: "I don't consent to a search."</p>
            </div>
            
            <div class="scenario">
                <strong>Home Search</strong>
                <p>Police generally need a warrant to search your home. Exception: consent or "exigent circumstances." Don't consent. Say: "I don't consent to a search" even if they push back.</p>
            </div>
            
            <div class="scenario">
                <strong>Arrest</strong>
                <p>Police will read Miranda rights if questioning planned. Once arrested, don't answer questions. Say: "I want a lawyer." Don't resist physically. Comply with orders but assert your rights verbally.</p>
            </div>
            
            <div class="scenario">
                <strong>Overdose Response</strong>
                <p>If overdose emergency occurs and police arrive: Good Samaritan protections apply. Police focus is saving life. You're protected from arrest for possession in overdose emergency (varies by state).</p>
            </div>
        </div>

        <h3>Key Principle: Don't Consent</h3>
        <p>Say "I don't consent to a search" even if police are pushy. Consent removes need for warrant. Without your consent, searches require warrants or exceptions. Police may search anyway, but never consent - it's weaker evidence in court.</p>
    </section>

    <!-- Substance Laws Overview -->
    <section class="legal-section">
        <h2>Substance-Related Laws Overview</h2>
        
        <h3>Federal vs. State Laws</h3>
        <ul>
            <li><strong>Federal:</strong> All controlled substances illegal under federal law. DEA enforces. Penalties vary by drug and amount</li>
            <li><strong>State:</strong> Each state has its own drug laws. Often more lenient than federal. May allow medical use or decriminalization</li>
            <li><strong>Local:</strong> Cities may have additional ordinances or enforcement priorities</li>
        </ul>

        <h3>Common Charges:</h3>
        <ul>
            <li><strong>Possession:</strong> Having drug for personal use. Lower charges than trafficking</li>
            <li><strong>Possession with Intent to Distribute:</strong> More serious. Larger amount or other evidence</li>
            <li><strong>Paraphernalia:</strong> Possession of equipment for drug use. Often minor offense</li>
            <li><strong>DUI/DWI:</strong> Driving under influence of substances. Serious felony</li>
            <li><strong>Trafficking:</strong> Selling or distributing. Most serious drug charges</li>
        </ul>

        <h3>Charges Related to Substance Use:</h3>
        <ul>
            <li>Probation/Parole violations if substance use violates conditions</li>
            <li>Child endangerment if children present during use</li>
            <li>Driving under influence charges</li>
            <li>Property crimes if stealing to support use</li>
        </ul>

        <div class="info-box danger">
            <strong>🚨 Laws Vary Significantly</strong>
            <p>Substance laws vary dramatically by state. What's decriminalized in one state is felony in another. Some states allow medical use. Always research your specific state's laws.</p>
        </div>
    </section>

    <!-- If Arrested -->
    <section class="legal-section">
        <h2>If You're Arrested</h2>
        
        <div class="checkpoints-box">
            <h4>✓ Steps to Take If Arrested</h4>
            <ul>
                <li><strong>Stay Calm:</strong> Don't resist physically. Comply with orders but assert rights verbally</li>
                <li><strong>Ask Why:</strong> Ask clearly why you're being arrested. Demand to know charges</li>
                <li><strong>Remain Silent:</strong> Don't answer questions. Say: "I want to remain silent"</li>
                <li><strong>Request Attorney:</strong> Say clearly: "I want a lawyer" and stop talking</li>
                <li><strong>Remember Details:</strong> Officer names, badge numbers, location, time, what happened</li>
                <li><strong>Make a Call:</strong> When allowed, call attorney or trusted person immediately</li>
                <li><strong>Don't Sign Anything:</strong> Don't sign papers without attorney present</li>
                <li><strong>Attend Arraignment:</strong> Your first court appearance where charges explained</li>
            </ul>
        </div>

        <h3>Your First Court Appearance (Arraignment):</h3>
        <ul>
            <li>Charges will be read to you</li>
            <li>You'll enter plea: guilty, not guilty, or no contest</li>
            <li>Bail/bond may be set</li>
            <li>Public defender may be appointed if can't afford attorney</li>
            <li>Don't plead guilty without attorney advice</li>
        </ul>

        <div class="info-box warning">
            <strong>⚠️ Don't Plead Guilty Immediately</strong>
            <p>Even if you used substances, don't plead guilty at first appearance. Consult attorney first. Many cases can be reduced or dismissed with proper representation.</p>
        </div>
    </section>

    <!-- Finding Legal Help -->
    <section class="legal-section">
        <h2>Getting Legal Help</h2>
        
        <div class="resources-list">
            <h4>Legal Assistance Options</h4>
            
            <div class="resource-item">
                <strong>Public Defender</strong>
                <p>Free attorney provided by court if can't afford. Assigned at arraignment. Quality varies but constitutionally protected right.</p>
            </div>
            
            <div class="resource-item">
                <strong>Legal Aid Society</strong>
                <p>Nonprofit organizations in most areas providing free legal services to low-income people. Search "[your area] legal aid" online.</p>
            </div>
            
            <div class="resource-item">
                <strong>Public Interest Law Firms</strong>
                <p>Organizations focused on substance use policy and defense. Some offer free representation in certain cases.</p>
            </div>
            
            <div class="resource-item">
                <strong>Private Attorneys</strong>
                <p>Criminal defense attorneys. Initial consultation often free or low-cost. Shop around for rates and fit.</p>
            </div>
            
            <div class="resource-item">
                <strong>Law School Clinics</strong>
                <p>Law schools often run clinics providing legal services under attorney supervision. Low cost or free.</p>
            </div>
            
            <div class="resource-item">
                <strong>Drug Court Programs</strong>
                <p>Some jurisdictions have specialized drug courts. May provide treatment alternative to incarceration. Attorney assists.</p>
            </div>
        </div>
    </section>

    <!-- Rights & Recovery -->
    <section class="legal-section">
        <h2>Legal Considerations in Recovery</h2>
        
        <h3>Drug Courts & Treatment Alternatives:</h3>
        <ul>
            <li>Many jurisdictions offer drug courts as alternative to incarceration</li>
            <li>Requires completion of treatment program</li>
            <li>Charges may be dismissed upon completion</li>
            <li>Discuss with attorney if available</li>
        </ul>

        <h3>Expungement & Record Clearing:</h3>
        <ul>
            <li>Many states allow expungement of drug convictions after time/treatment</li>
            <li>Cleared records don't appear on background checks</li>
            <li>Can improve employment/housing prospects</li>
            <li>Attorney can advise on eligibility</li>
        </ul>

        <h3>Second Chance Laws:</h3>
        <ul>
            <li>Many states have "ban the box" laws preventing employers asking about convictions upfront</li>
            <li>Some prevent conviction from disqualifying people from housing</li>
            <li>Varies by state and locality</li>
        </ul>

        <div class="info-box success">
            <strong>✓ Recovery Includes Legal Healing</strong>
            <p>Part of recovery is addressing legal issues. Treatment courts, expungement, and record clearing help rebuild. Discuss with your attorney options for legal recovery.</p>
        </div>
    </section>

    <!-- Resources -->
    <section class="legal-section">
        <h2>Additional Resources</h2>
        
        <h3>Know Your Rights Resources:</h3>
        <ul>
            <li><strong>ACLU:</strong> aclu.org - Constitutional rights information</li>
            <li><strong>Know Your Rights:</strong> knowyourights.org - Interactive tools</li>
            <li><strong>Stop & Frisk:</strong> stopandfrisk.org - Street encounters guide</li>
            <li><strong>Your State Bar Association:</strong> Search "[state] bar association" for attorney referrals</li>
        </ul>

        <h3>Substance Use & Legal Resources:</h3>
        <ul>
            <li><strong>Drug Policy Alliance:</strong> drugpolicy.org - Policy and legal information</li>
            <li><strong>NORML:</strong> norml.org - Cannabis legal information</li>
            <li><strong>Harm Reduction Coalition:</strong> harmreduction.org - Legal and policy resources</li>
        </ul>

        <div class="info-box">
            <strong>💡 Knowledge Is Power</strong>
            <p>Understanding your rights protects you. Share this knowledge with others. Empowered communities make better decisions and protect each other legally.</p>
        </div>
    </section>

    <!-- Disclaimer -->
    <section class="legal-section disclaimer-section">
        <h2>⚠️ Important Legal Disclaimer</h2>
        <p>This information is educational and not legal advice. Laws vary by jurisdiction and change over time. Consult with qualified attorneys for specific legal situations.</p>
        <ul>
            <li>This is general information only, NOT legal advice</li>
            <li>Consult attorney in your jurisdiction for specific advice</li>
            <li>Laws vary significantly by state and locality</li>
            <li>Rights and procedures change - verify current information</li>
            <li>Always seek professional legal representation</li>
        </ul>
        <p><strong>You have rights. Assert them respectfully. Get legal help. Your future matters.</strong></p>
    </section>
</div>
@endsection
