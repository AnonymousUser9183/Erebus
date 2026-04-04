<div align="center">
  <h1>Erebus Marketplace Script</h1>
</div>
<div align="center">
  <h1>Laravel 12 - Monero Only - Open Source Marketplace Script
</h1>
</div>

<div align="center">
    <h2>Donation Address</h2>
</div>
<div align="center">
Support continued development through Monero donations:

**Monero (XMR)**
```
45umQEDfN52gzHMpUxkK8TUAeZjFgzb2VDmZArgx4iTHeGY4gb2KrtqZC691Ff9pHaJeUFF1oBZAGQHTHzps7icg5cdptMG
```

All donations support active development and security improvements.
</div>


<div align="center">
  <h1><a href="docs/INSTALLATION.md">Installation Guide</a></h1>
</div>

<div align="center">
  <img src="public/images/logo.png" alt="Erebus Marketplace Logo">
</div>

<div align="center">
  <h1>Erebus Marketplace Script</h1>
  <p><strong>Author:</strong> AnonymousUser9183</p>
  <p><strong>Organization:</strong> The Erebus Development Team</p>
  <p><strong>License:</strong> <a href="LICENSE.md">Erebus Marketplace Script License 1.0</a></p>
</div>

---

<div align="center">
    <h2>About Erebus Marketplace Script</h2>
</div>

<div align="center">
**Erebus Marketplace Script** is a modern, privacy-focused marketplace platform built with **PHP 8.3** and **Laravel 12.11.1**. Erebus Marketplace Script is a rewritten version of the lost Kabus Marketplace Script and provides a complete marketplace solution for anonymous commerce using Monero.
  
  The purpose of creating Erebus Marketplace Script is to replace Kabus Marketplace Script and to provide safe and anonymous commerce software and ensure continued evolution of privacy-respecting marketplace technology. It is not created for any illegal purpose, nor does it encourage such activities. The platform facilitates the sale of legal products such as legally aquired electronics and jewlery online as anonymously and securely as possible.

Built with a security-first approach, including hardened Laravel controllers, maximum CSP policies, and zero-JavaScript architecture for optimal Tor compatibility.
</div>


<div align="center">
    <h2>Core Features</h2>
</div>

### Monero Integration
- **Vendor Registration Payment**: Monero Wallet RPC integration generating secure wallet addresses for vendor fee payments
- **Product Advertising Payment**: Integrated payment system for homepage product promotion via Monero transactions
- **Product Purchasing**: Secure, anonymous product transactions using Monero payment system
- **Return Address Validation**: Cryptonote-based validation for user Monero return addresses
- **Walletless Escrow**: No user wallets stored; payments escrowed per transaction until resolution

### Marketplace Functions
- **User Dashboard**: Comprehensive control panel for account management and activity tracking
- **Vendor Profiles**: Full vendor pages with product catalogs and reputation systems
- **Product Management**: Advanced search, filtering, bulk options, and delivery methods
- **Messaging System**: Secure, encrypted communication between marketplace participants
- **Admin Panel**: Complete administrative interface with user/vendor/dispute management
- **Vendor Panel**: Dedicated vendor interface for inventory, orders, and payouts
- **Reference System**: Optional referral code requirement for registration with tracking
- **Support System**: Integrated help desk and support ticket functionality
- **Disputes System**: Comprehensive order dispute resolution with administrative arbitration
- **Reviews System**: Product and vendor rating system with verified purchase integration
- **Cart System**: Persistent shopping cart with bulk purchasing support

### Security & Privacy
- **Content Security Policy (CSP)**: Maximum hardening with zero unsafe-inline/eval directives
- **No JavaScript Required**: Pure server-side rendering; Tor-compatible without JS
- **PGP Integration**: Mandatory PGP key confirmation and vendor verification
- **Two-Factor Authentication**: PGP-based 2FA for enhanced account security
- **HSTS & Transport Security**: Forced HTTPS with preload and strict transport policies
- **Path Traversal Prevention**: Hardened file handling with comprehensive validation
- **MIME Type Protection**: Prevents content sniffing and file upload exploits
- **Referrer Policy Control**: IP leak prevention through strict referrer policies
- **Permissions Policy**: Feature lockdown disabling 20+ browser capabilities
- **CORS Isolation**: Cross-Origin-Opener-Policy and Cross-Origin-Resource-Policy hardening
- **Mnemonic Recovery**: Built-in mnemonic phrase generation for key recovery
- **SQL Injection Prevention**: Prepared statements and Laravel Eloquent ORM
- **CSRF Protection**: Laravel CSRF token middleware on all state-changing requests
- **XSS Protection**: Multiple layers including CSP, X-XSS-Protection, type sniffing prevention
- **Rate Limiting**: DDoS protection through request throttling and IP-based rate limiting

### Advanced Features
- **Multi-Product Types**: Support for Digital, Cargo (physical), and Dead Drop deliveries
- **Category Hierarchy**: Three-level category system (parent → child → subcategory)
- **Bulk Pricing Options**: Volume-based pricing tiers for products
- **Image Upload System**: Secure image handling with MIME type validation
- **Product Variants**: Support for measurement units and bulk option specifications
- **Vendor Vacation Mode**: Vendor operation pause without account deletion
- **Private Shop Mode**: Vendors can restrict shop access to specific users
- **Vendor Policy**: Customizable vendor policies and terms
- **Analytics Dashboard**: Order statistics, revenue tracking, and performance metrics

---

<div align="center">
    <h2>Technical Stack</h2>
</div>

| Component | Version | Purpose |
|-----------|---------|---------|
| **PHP** | 8.3+ | Server-side language |
| **Laravel** | 12.11.1 | Web framework |
| **Monero** | Latest | Cryptocurrency integration |
| **MySQL** | 8.0+ | Database |
| **Cryptonote-PHP** | Latest | Address validation |

---

<div align="center">
    <h2>Installation & Deployment</h2>
</div>

### Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/AnonymousUser9183/Erebus.git
   cd Erebus
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup database**
   ```bash
   php artisan migrate
   php artisan seed --class=DatabaseSeeder
   ```

5. **Configure Monero RPC**
   - See [Monero Wallet RPC Guide](docs/CONNECTING-MONERO-RPC.md)
   - Update `.env` with RPC credentials

6. **Configure security headers**
   - Review `app/Http/Middleware/TrustProxies.php`
   - Adjust CSP report-only mode for testing if needed

For detailed installation instructions, see [INSTALLATION.md](docs/INSTALLATION.md)

---

<div align="center">
    <h2>Architecture Overview</h2>
</div>

### Controllers + Middleware + More (Modernized for Laravel 12.11.1)

**Added Features**
- Private mirror system
- Orders management panel - under construction
- Harm reduction education system
- Comprehensive logging and exception handling

**Middleware**
- TrustProxies.php as primary csp middleware
- Proxy configuration for load balancers/Tor exits
- 8 dedicated security header methods
- Cache control for sensitive paths
- 21 permissions restrictions

**Other**
- Updated category management to list properly parent cat - sub-cat - sub-sub-cat structure
- Completely rewritten design
- Floating sponsor banner added
- New navbar setup, left and right bars removed

---

<div align="center">
    <h2>Security Considerations</h2>
</div>

### For Operators

- [ ] Configure all environment variables in `.env`
- [ ] Set up dedicated Monero wallet for operations
- [ ] Enable HTTPS with valid certificates
- [ ] Implement robust backup and recovery procedures
- [ ] Monitor logs for suspicious activity
- [ ] Keep PHP/Laravel dependencies updated
- [ ] Implement rate limiting on all endpoints
- [ ] Regular security audits recommended
- [ ] Establish clear Terms of Service and policies

### For Users

- [ ] Enable 2FA with PGP key
- [ ] Keep recovery mnemonics secure
- [ ] Verify vendor PGP keys before transactions
- [ ] Review product descriptions carefully
- [ ] Use escrow system - never pay outside platform
- [ ] Report suspicious vendors to administration

---

<div align="center">
    <h2>License & Attribution</h2>
</div>

The Erebus Marketplace Script is released under the **[Erebus Marketplace Script License 1.0](LICENSE.md)**

**Key License Terms:**
- ✅ **Permitted**: Run your own marketplace, modify for operations, study code, contribute improvements
- ❌ **Prohibited**: Commercial distribution, removing attribution, AI/ML training
- ✅ **Attribution**: All derivative works must clearly credit "Erebus Marketplace Script by AnonymousUser9183"
- ✅ **Enforcement**: Strong legal remedies for violations including DMCA and attorney fees

For complete license details, see [LICENSE.md](LICENSE.md)

---

<div align="center">
    <h2>Project Status</h2>
</div>

**Status:** Active Development - Beta

Erebus Marketplace Script is under active/beta development with regular updates and security improvements. The project prioritizes:

1. **Security** - Hardened controls and CSP policies
2. **Privacy** - Zero-JavaScript Tor compatibility
3. **Performance** - Optimized database queries and caching
4. **Reliability** - Comprehensive error handling and logging
5. **Maintainability** - Clean code with full type hints

---

<div align="center">
    <h2>Contributing</h2>
</div>

Contributions are welcome via **proper GitHub forks only**. To contribute:

1. Fork the repository officially
2. Create a feature branch
3. Make improvements with proper attribution
4. Submit a pull request with detailed description
5. Follow all license terms in your contributions

**Note:** Forking via platform mechanisms is required. Creating new repositories with copied code violates the license.

---

<div align="center">
    <h2>Support & Issues</h2>
</div>

For bug reports, feature requests, or questions:

- **GitHub Issues**: [github.com/AnonymousUser9183/Erebus/issues](https://github.com/AnonymousUser9183/market/issues)
- **Documentation**: [docs/](docs/)
- **Email**: anonymoususer9183@protonmail.com (commercial inquiries)

---

<div align="center">
    <h2>Commercial Licensing</h2>
</div>

For commercial licensing, custom development, or permissions beyond the open-source license terms:

**Email:** anonymoususer9183@protonmail.com

Commercial licenses are available for entities wishing to distribute, commercialize, or use the Software beyond open-source scope.

---

<div align="center">
    <h2>Roadmap</h2>
</div>

### Planned Features

- [ ] Multi-signature escrow support
- [ ] Atomic swap integration
- [ ] Enhanced vendor reputation system
- [ ] Mobile Tor Browser optimization
- [ ] Advanced analytics and reporting
- [ ] Payment splitting for multi-vendor orders
- [ ] Decentralized governance voting
- [ ] Enhanced dispute resolution automation
- [ ] Integration with additional cryptocurrencies

---

<div align="center">
    <h2>Privacy Philosophy</h2>
</div>

```
Privacy is a human right. It cannot be taken away from anyone, 
nor should its protection ever be suggested as controversial.
```

Erebus Marketplace Script is built on the principle that financial privacy and freedom of commerce are fundamental human rights. The platform operates with zero tolerance for privacy compromises.

---

<div align="center">
    <h2>Disclaimer</h2>
</div>

Erebus Marketplace Script is provided "AS IS" without warranty of any kind. Users are responsible for:

- Ensuring compliance with all applicable laws
- Implementing appropriate content moderation
- Maintaining security of user data
- Responsible disclosure of vulnerabilities

The creators and maintainers assume no liability for misuse, illegal activity, or security breaches.

---

<div align="center">
    <h2>Credits</h2>
</div>

**Original Developer:** AnonymousUser9183

**Contributors:** Community forks and improvements welcome

**Special Thanks:** Monero community for cryptocurrency integration support

---

<div align="center">
  <h3>© 2026 The Erebus Development Team, All Rights Reserved.</h3>
  <p><strong>Author:</strong> AnonymousUser9183</p>
  <p><a href="LICENSE.md">License Terms</a> | <a href="docs/SECURITY.md">Security Policy</a> | <a href="docs/INSTALLATION.md">Installation</a></p>
</div>
