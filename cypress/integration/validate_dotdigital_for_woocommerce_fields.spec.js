describe('Validate ddg fields', () => {
  beforeEach('Activate dotdigital for woocommerce plugin', () => {
    cy.activatePlugin('dotdigital-for-woocommerce')
    cy.visit('/wp-admin/admin.php?page=dotdigital-for-woocommerce-settings')
  })
  it('dotdigital fields exist', () => {
    cy.contains('Show marketing checkbox at checkout')
    cy.contains('Show marketing checkbox at user registration')
    cy.contains('Marketing checkbox text')
    cy.contains('Enable site and ROI tracking')
    cy.contains('Select Region')
    cy.contains('Web behavior tracking profile id')
    cy.contains('Enable abandoned cart')
    cy.contains('Abandoned cart Program ID')
    cy.contains('Allow abandoned cart delay (minutes)')
    cy.contains('Allow abandoned cart for non-subscribed contacts')
  })
})
