import sitename from './sitename.twig';

import sitenameData from './sitename.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Sitename' };

export const Sitename = () => sitename(sitenameData);
