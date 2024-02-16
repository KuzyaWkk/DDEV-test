import socialBlock from './social-block.twig';

import socialBlockData from './social-block.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/SocialBlock' };

export const Social = () => socialBlock(socialBlockData);
