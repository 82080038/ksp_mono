# Production Deployment Checklist - Validation Rules

## Pre-Deployment
- [ ] Verify all staging tests pass
- [ ] Backup current config.php
- [ ] Confirm rollback plan
- [ ] Notify users

## Deployment Steps
1. Maintenance mode on
2. Deploy updated validation_constants.php
3. Run database migrations
4. Clear application cache
5. Maintenance mode off

## Post-Deployment
- [ ] Monitor error rates
- [ ] Verify form submissions
- [ ] Check performance metrics
- [ ] Gather user feedback

## Rollback Plan
1. Maintenance mode on
2. Restore config.php backup
3. Clear cache
4. Maintenance mode off
