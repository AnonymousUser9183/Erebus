@extends('layouts.auth')

@section('title', 'Crisis Hotline Directory - Immediate Help Available')

@section('breadcrumb', 'Crisis Hotlines')

@section('content')
<style>
    .crisis-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0;
    }

    .crisis-header {
        background: linear-gradient(135deg, #0d5b7c 0%, #1a7a99 100%);
        color: white;
        padding: 32px 24px;
        border-bottom: 3px solid #2a9db8;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px 8px 0 0;
    }

    .crisis-header h1 {
        font-size: 32px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .crisis-header p {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }

    .crisis-section {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 32px;
        margin-bottom: 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
    }

    .crisis-section h2 {
        color: #0d5b7c;
        font-size: 24px;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 2px solid #2a9db8;
    }

    .crisis-section h3 {
        color: #1a7a99;
        font-size: 18px;
        margin-top: 16px;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .crisis-section p {
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

    .crisis-section ul,
    .crisis-section ol {
        margin-left: 16px;
        margin-bottom: 16px;
    }

    .crisis-section li {
        margin-bottom: 12px;
        color: #666666;
        line-height: 1.7;
    }

    .hotline-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .hotline-card {
        background-color: #f5f5f5;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        padding: 20px;
        transition: all 0.3s ease;
    }

    .hotline-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #2a9db8;
    }

    .hotline-card h4 {
        color: #0d5b7c;
        font-size: 17px;
        margin: 0 0 8px 0;
        font-weight: 600;
    }

    .hotline-number {
        font-size: 24px;
        color: #dc2626;
        font-weight: bold;
        font-family: monospace;
        margin: 12px 0;
        padding: 12px;
        background-color: #fff;
        border-radius: 4px;
        border-left: 4px solid #dc2626;
    }

    .hotline-card p {
        margin: 8px 0 0 0;
        font-size: 14px;
        color: #666666;
        line-height: 1.6;
    }

    .hotline-card ul {
        margin-left: 0;
        padding-left: 20px;
        margin: 8px 0 0 0;
        margin-bottom: 0;
    }

    .hotline-card li {
        margin-bottom: 6px;
        font-size: 13px;
        color: #666666;
    }

    .emergency-banner {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: white;
        border-radius: 6px;
        padding: 24px;
        margin-bottom: 24px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .emergency-banner h3 {
        color: white;
        font-size: 22px;
        margin: 0 0 16px 0;
    }

    .emergency-banner .number {
        font-size: 36px;
        font-weight: bold;
        font-family: monospace;
        margin: 12px 0;
    }

    .emergency-banner p {
        color: white;
        margin: 8px 0;
        font-size: 15px;
    }

    .when-to-call {
        background-color: #f0f9fb;
        border: 2px solid #2a9db8;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .when-to-call h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .when-to-call ul {
        margin-left: 16px;
        margin-bottom: 0;
    }

    .when-to-call li {
        margin-bottom: 8px;
        font-size: 14px;
        color: #666666;
    }

    .how-to-call {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .call-tip {
        background-color: #dcfce7;
        border: 2px solid #22c55e;
        border-radius: 6px;
        padding: 16px;
    }

    .call-tip h4 {
        color: #22c55e;
        font-size: 15px;
        margin: 0 0 10px 0;
        font-weight: 600;
    }

    .call-tip p {
        margin: 0;
        font-size: 13px;
        color: #333333;
        line-height: 1.6;
    }

    .international-section {
        background-color: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .international-section h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin: 0 0 16px 0;
        font-weight: 600;
    }

    .country-item {
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e0e0e0;
    }

    .country-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .country-item strong {
        color: #0d5b7c;
        font-size: 14px;
    }

    .country-item p {
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
        .crisis-header h1 {
            font-size: 24px;
        }

        .crisis-header p {
            font-size: 14px;
        }

        .crisis-section {
            padding: 16px;
        }

        .crisis-section h2 {
            font-size: 20px;
        }

        .hotline-grid {
            grid-template-columns: 1fr;
        }

        .how-to-call {
            grid-template-columns: 1fr;
        }

        .emergency-banner .number {
            font-size: 28px;
        }
    }

    @media (max-width: 480px) {
        .crisis-header h1 {
            font-size: 20px;
        }

        .crisis-section {
            padding: 12px;
        }

        .crisis-section h2 {
            font-size: 18px;
        }

        .crisis-section h3 {
            font-size: 16px;
        }

        .info-box {
            padding: 12px;
        }

        .hotline-number {
            font-size: 20px;
        }

        .emergency-banner .number {
            font-size: 24px;
        }
    }
</style>

<div class="crisis-page">
    <!-- Header -->
    <div class="crisis-header">
        <h1>📞 Crisis Hotline Directory</h1>
        <p>Immediate support and help available 24/7. You are not alone. Trained counselors ready to listen.</p>
    </div>

    <!-- Emergency Banner -->
    <section class="crisis-section">
        <div class="emergency-banner">
            <h3>If You Are in Immediate Danger</h3>
            <div class="number">911</div>
            <p><strong>Call or Text 911</strong></p>
            <p>For emergencies: overdose, medical crisis, self-harm, suicide attempt</p>
        </div>
    </section>

    <!-- Main Crisis Resources -->
    <section class="crisis-section">
        <h2>National Crisis Hotlines</h2>
        <p>Free, confidential support available right now. You can call, text, or chat online. No judgment. No penalties.</p>
        
        <div class="hotline-grid">
            <div class="hotline-card">
                <h4>🆘 Suicide & Crisis Lifeline</h4>
                <div class="hotline-number">988</div>
                <p><strong>Call or Text 988</strong></p>
                <p>Free, confidential, 24/7 support for suicidal thoughts, mental health crisis, or emotional distress. Available to anyone in the US.</p>
                <ul>
                    <li>Trained crisis counselors</li>
                    <li>Same-day appointment connections</li>
                    <li>Spanish language available</li>
                </ul>
            </div>

            <div class="hotline-card">
                <h4>💊 SAMHSA National Helpline</h4>
                <div class="hotline-number">1-800-662-4357</div>
                <p>Free, confidential, 24/7 treatment referral and information service</p>
                <p>Substance use and mental health disorders. English and Spanish available.</p>
                <ul>
                    <li>Treatment program referrals</li>
                    <li>Location-specific resources</li>
                    <li>Insurance & payment info</li>
                </ul>
            </div>

            <div class="hotline-card">
                <h4>💬 Crisis Text Line</h4>
                <div class="hotline-number">741741</div>
                <p><strong>Text HOME to 741741</strong></p>
                <p>Free crisis support via text message. Immediate connection to trained crisis counselor.</p>
                <ul>
                    <li>Text-based crisis support</li>
                    <li>24/7 availability</li>
                    <li>For any crisis type</li>
                </ul>
            </div>
        </div>

        <div class="info-box success">
            <strong>✓ Why Call a Hotline?</strong>
            <p>Crisis counselors are trained to listen without judgment. They understand what you're going through. Talking to someone can help you feel less alone and find ways to cope with overwhelming feelings.</p>
        </div>
    </section>

    <!-- Substance Use Specific Resources -->
    <section class="crisis-section">
        <h2>Substance Use & Addiction Crisis Support</h2>
        
        <div class="hotline-grid">
            <div class="hotline-card">
                <h4>🚨 Overdose Emergency</h4>
                <div class="hotline-number">911</div>
                <p><strong>Call 911 Immediately</strong></p>
                <p>If someone is unconscious, not breathing, or showing overdose signs, call 911 without delay.</p>
                <ul>
                    <li>Administer Narcan if available</li>
                    <li>Place person on side</li>
                    <li>Stay with person until help arrives</li>
                </ul>
            </div>

            <div class="hotline-card">
                <h4>🆘 Substance Use Crisis</h4>
                <div class="hotline-number">1-800-662-4357</div>
                <p><strong>SAMHSA National Helpline</strong></p>
                <p>Free, confidential support for substance use crisis, withdrawal, or addiction questions.</p>
                <ul>
                    <li>24/7 referral service</li>
                    <li>Treatment options</li>
                    <li>Emergency support</li>
                </ul>
            </div>

            <div class="hotline-card">
                <h4>💭 Mental Health in Recovery</h4>
                <div class="hotline-number">988</div>
                <p>Suicide & Crisis Lifeline for mental health support during recovery</p>
                <p>Depression, anxiety, cravings, relapse thoughts - call anytime.</p>
                <ul>
                    <li>Mental health crisis</li>
                    <li>Suicidal thoughts</li>
                    <li>Recovery support</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- When to Call -->
    <section class="crisis-section">
        <h2>When to Call a Crisis Hotline</h2>
        
        <div class="when-to-call">
            <h4>You might benefit from crisis support if you are:</h4>
            <ul>
                <li>Having thoughts of suicide or self-harm</li>
                <li>Experiencing severe anxiety, panic, or fear</li>
                <li>Feeling overwhelming sadness or depression</li>
                <li>In crisis due to substance use or withdrawal</li>
                <li>Struggling with cravings or thinking about relapse</li>
                <li>Experiencing domestic violence or abuse</li>
                <li>Dealing with grief or loss</li>
                <li>Feeling isolated or hopeless</li>
                <li>Struggling with identity or coming out</li>
                <li>Experiencing any emotional or mental health crisis</li>
            </ul>
        </div>

        <div class="info-box warning">
            <strong>⚠️ There's No "Right Reason" to Call</strong>
            <p>You don't need to be at rock bottom or in immediate danger. If you're struggling and need support, that's reason enough to call. Crisis counselors are there for any difficulty - don't minimize what you're experiencing.</p>
        </div>
    </section>

    <!-- How to Call -->
    <section class="crisis-section">
        <h2>Tips for Calling a Crisis Hotline</h2>
        
        <div class="how-to-call">
            <div class="call-tip">
                <h4>✓ Be Honest</h4>
                <p>Tell the counselor what you're really feeling and thinking. They've heard it all and won't judge you. The more you share, the better they can help.</p>
            </div>
            <div class="call-tip">
                <h4>✓ Take Your Time</h4>
                <p>There's no rush. Crisis counselors aren't on a timer. They're there to listen for as long as you need to talk. Take the time you need.</p>
            </div>
            <div class="call-tip">
                <h4>✓ It's Confidential</h4>
                <p>What you share is confidential (with rare exceptions like immediate danger). You can be completely honest without fear of consequences.</p>
            </div>
            <div class="call-tip">
                <h4>✓ Be Specific</h4>
                <p>Describe what's happening, how you feel, and what triggered the crisis. Specific details help counselors understand and provide better support.</p>
            </div>
            <div class="call-tip">
                <h4>✓ Ask for Referrals</h4>
                <p>Ask about local resources, treatment programs, support groups, or therapists. They have lists and connections to help you find ongoing support.</p>
            </div>
            <div class="call-tip">
                <h4>✓ It's OK to Call Again</h4>
                <p>If you're in crisis multiple times, keep calling. That's what the service is for. Regular callers are common and welcomed.</p>
            </div>
        </div>

        <div class="info-box success">
            <strong>✓ You Will Feel Better</strong>
            <p>Sometimes just talking to someone who understands and cares helps you feel less alone. Many people report that calling a crisis line helped them get through their darkest moments and find hope again.</p>
        </div>
    </section>

    <!-- Specific Populations -->
    <section class="crisis-section">
        <h2>Crisis Support for Specific Groups</h2>
        
        <h3>LGBTQ+ Youth & Adults:</h3>
        <ul>
            <li><strong>Trevor Project:</strong> 1-866-488-7386 - Crisis support for LGBTQ+ individuals (24/7)</li>
            <li><strong>Trans Lifeline:</strong> 877-565-8860 - Support for trans and non-binary people (24/7)</li>
        </ul>

        <h3>Veterans:</h3>
        <ul>
            <li><strong>Veterans Crisis Line:</strong> 988 then press 1 - Support for active duty & veterans (24/7)</li>
            <li><strong>Military Crisis Line:</strong> 1-800-273-8255 ext. 1 - For military members and families</li>
        </ul>

        <h3>Domestic Violence:</h3>
        <ul>
            <li><strong>National Domestic Violence Hotline:</strong> 1-800-799-7233 - Confidential support (24/7)</li>
            <li><strong>Text START to 88788:</strong> Anonymous text support for domestic violence</li>
        </ul>

        <h3>Sexual Assault:</h3>
        <ul>
            <li><strong>RAINN (Rape, Abuse & Incest National Network):</strong> 1-800-656-4673 (24/7)</li>
            <li><strong>Online Chat:</strong> Online.rainn.org for confidential chat support</li>
        </ul>

        <h3>Eating Disorders:</h3>
        <ul>
            <li><strong>National Eating Disorders Association:</strong> 1-800-931-2237 - Call or text (24/7)</li>
        </ul>
    </section>

    <!-- International Resources -->
    <section class="crisis-section">
        <h2>International Crisis Resources</h2>
        
        <div class="international-section">
            <h4>Crisis Lines by Country</h4>
            
            <div class="country-item">
                <strong>Canada</strong>
                <p>Crisis Text Line Canada: Text HOME to 741741<br/>1-833-456-4566 (Suicide Prevention Lifeline)</p>
            </div>
            
            <div class="country-item">
                <strong>United Kingdom</strong>
                <p>Samaritans: 116 123<br/>Crisis Text Line: Text SHOUT to 85258</p>
            </div>
            
            <div class="country-item">
                <strong>Australia</strong>
                <p>Lifeline: 13 11 14<br/>Text: 0477 13 11 14</p>
            </div>
            
            <div class="country-item">
                <strong>New Zealand</strong>
                <p>1 Nova: 1-737-669-6362<br/>Lifeline Aotearoa: 0800 543 354</p>
            </div>
            
            <div class="country-item">
                <strong>Ireland</strong>
                <p>Samaritans Ireland: 1800 247 247<br/>Text HELLO to 50808</p>
            </div>
            
            <div class="country-item">
                <strong>Other Countries</strong>
                <p>Befrienders International: www.befrienders.org (worldwide hotline directory)</p>
            </div>
        </div>
    </section>

    <!-- After You Call -->
    <section class="crisis-section">
        <h2>After You Call: Next Steps</h2>
        
        <h3>What Happens Next:</h3>
        <ul>
            <li><strong>You Make Choices:</strong> Crisis counselors won't force you to do anything. The plan is your choice</li>
            <li><strong>Safety First:</strong> If in danger, they may discuss safety planning or connecting you with emergency services</li>
            <li><strong>Resource Referral:</strong> They can provide local treatment, support groups, or therapists</li>
            <li><strong>Follow-Up:</strong> Some services will follow up; others help in the moment</li>
            <li><strong>Ongoing Support:</strong> One call doesn't solve everything - ongoing support is often needed</li>
        </ul>

        <h3>Building Long-Term Support:</h3>
        <ul>
            <li>Consider connecting with a therapist or counselor</li>
            <li>Join a support group for your specific struggle</li>
            <li>Tell trusted people in your life what you're going through</li>
            <li>Create a safety plan with coping strategies</li>
            <li>Keep crisis hotline numbers accessible for future use</li>
        </ul>

        <div class="info-box">
            <strong>💡 Crisis Calls Are Just the Beginning</strong>
            <p>Crisis hotlines provide immediate support, but long-term recovery often requires ongoing help. Use the resources they connect you to and don't hesitate to reach out again.</p>
        </div>
    </section>

    <!-- Disclaimer -->
    <section class="crisis-section disclaimer-section">
        <h2>⚠️ Important Information</h2>
        <p>Crisis hotlines provide immediate emotional support but are not a substitute for professional mental health or medical treatment. Information provided is for guidance only.</p>
        <ul>
            <li>In immediate life-threatening emergencies, always call 911</li>
            <li>Crisis lines complement but do not replace professional mental health care</li>
            <li>Always seek professional medical care for physical health emergencies</li>
            <li>Numbers and services may change - verify before calling</li>
            <li>You deserve professional help - don't rely only on crisis lines long-term</li>
        </ul>
        <p><strong>You matter. Your life has value. Help is available right now. Call 988 or 911.</strong></p>
    </section>
</div>
@endsection
